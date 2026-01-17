<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\Tiket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
  {
    $user = Auth::user() ?? \App\Models\User::first();
    $orders = Order::where('user_id', $user->id)->with('event')->orderBy('created_at', 'desc')->get();
    
    return view('orders.index', compact('orders'));
  }

  // show a specific order
  public function show(Order $order)
  {
    $order->load('detailOrders.tiket', 'event');
    return view('orders.show', compact('order'));
  }

  // store an order (AJAX POST)
  public function store(Request $request)
  {

    $data = $request->validate([
      'event_id' => 'required|exists:events,id',
      'items' => 'required|array|min:1',
      'items.*.tiket_id' => 'required|integer|exists:tikets,id',
      'items.*.jumlah' => 'required|integer|min:1',
    ]);

    $user = Auth::user();

    try {
      // transaction
      $order = DB::transaction(function () use ($data, $user) {
        // OPTIMASI: Ambil semua tiket sekaligus (1 query instead of N queries)
        $tiketIds = collect($data['items'])->pluck('tiket_id')->unique()->toArray();
        $tikets = Tiket::lockForUpdate()->whereIn('id', $tiketIds)->get()->keyBy('id');
        
        $total = 0;
        $detailOrdersData = [];
        $tiketUpdates = [];
        
        // validate stock and prepare data
        foreach ($data['items'] as $it) {
          $t = $tikets->get($it['tiket_id']);
          
          if (!$t) {
            throw new \Exception("Tiket tidak ditemukan.");
          }
          
          if ($t->stok < $it['jumlah']) {
            throw new \Exception("Stok tidak cukup untuk tipe: {$t->tipe}");
          }
          
          $subtotal = ($t->harga ?? 0) * $it['jumlah'];
          $total += $subtotal;
          
          // Prepare detail order data for bulk insert
          $detailOrdersData[] = [
            'tiket_id' => $t->id,
            'jumlah' => $it['jumlah'],
            'subtotal_harga' => $subtotal,
          ];
          
          // Prepare stock updates
          $tiketUpdates[$t->id] = max(0, $t->stok - $it['jumlah']);
        }

        // Create order
        $order = Order::create([
          'user_id' => $user->id,
          'event_id' => $data['event_id'],
          'order_date' => Carbon::now(),
          'total_harga' => $total,
        ]);

        // OPTIMASI: Bulk insert detail orders
        foreach ($detailOrdersData as &$detail) {
          $detail['order_id'] = $order->id;
          $detail['created_at'] = Carbon::now();
          $detail['updated_at'] = Carbon::now();
        }
        DetailOrder::insert($detailOrdersData);

        // OPTIMASI: Bulk update stock
        foreach ($tiketUpdates as $tiketId => $newStok) {
          DB::table('tikets')->where('id', $tiketId)->update(['stok' => $newStok]);
        }

        return $order;
      });

      // flash success message to session so it appears after redirect
      session()->flash('success', 'Pesanan berhasil dibuat.');

      return response()->json(['ok' => true, 'order_id' => $order->id, 'redirect' => route('orders.index')]);
    } catch (\Exception $e) {
      return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
    }
  }
}
