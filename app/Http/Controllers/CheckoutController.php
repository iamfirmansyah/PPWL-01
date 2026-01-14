<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    // Menampilkan halaman checkout
    public function index()
    {
        $cart = Session::get('cart', []);
        return view('user.checkout', compact('cart'));
    }

    // Proses checkout
    public function process(Request $request)
    {
        $cart = Session::get('cart', []);

        if (!$cart || count($cart) === 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'metode' => 'required|string',
        ]);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $orderId = 'ORD-' . time() . '-' . rand(1000, 9999);

        $order = Order::create([
            'id' => $orderId,
            'tanggal' => now()->toDateString(),
            'total' => $total,
            'status_pembayaran' => 'pending',
            'user_id' => Auth::id(),
        ]);

        foreach ($cart as $item) {
            OrderProduct::create([
                'jumlah' => $item['quantity'],
                'harga_satuan' => $item['price'],
                'order_id' => $order->id,
                'product_id' => $item['id'],
            ]);

            $product = Product::find($item['id']);
            if ($product) {
                $product->stok = $product->stok - $item['quantity'];
                $product->save();
            }
        }

        Session::forget('cart');

        return redirect()->route('checkout.sukses', ['order' => $order->id])->with('success', 'Pesanan berhasil dibuat!');
    }

    public function sukses($orderId)
    {
        $order = Order::with('products')->findOrFail($orderId);
        return view('user.sukses', compact('order'));
    }

    public function updatePaymentProof(Request $request, Order $order)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('bukti_pembayaran')) {
            if ($order->bukti_pembayaran) {
                Storage::disk('public')->delete($order->bukti_pembayaran);
            }

            $path = $request->file('bukti_pembayaran')->store('payment', 'public');

            $order->update([
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'diproses',
            ]);
        }

        return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah!');
    }
}
