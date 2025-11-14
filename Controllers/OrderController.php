<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::orderByDesc('order_date')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|unique:orders,order_number',
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,cancelled',
            'order_date' => 'required|date',
        ]);

        Order::create($validated);
        return redirect()->route('orders.index')->with('success', 'Pedido creado exitosamente');
    }

    public function show(string $id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show', compact('order'));
    }

    public function edit(string $id)
    {
        $order = Order::findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'order_number' => 'required|unique:orders,order_number,' . $order->id,
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,cancelled',
            'order_date' => 'required|date',
        ]);

        $order->update($validated);
        return redirect()->route('orders.index')->with('success', 'Pedido actualizado exitosamente');
    }

    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pedido eliminado exitosamente');
    }
}
