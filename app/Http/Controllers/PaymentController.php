<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Milestone;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $payments = Payment::orderBy('created_at', 'desc')->get();
        return view('payments.index', compact('payments'));
    }

    public function create()
    {

        $milestones = Milestone::where('is_paid', false)->get();
        return view('payments.create', compact('milestones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'milestone_id'   => 'required|exists:milestones,id',
            'amount'         => 'required|numeric|min:1',
            'transaction_id' => 'required|unique:payments,transaction_id',
            'payment_method' => 'required'
        ]);

        Payment::create([
            'milestone_id'   => $request->milestone_id,
            'amount'         => $request->amount,
            'transaction_id' => $request->transaction_id,
            'payment_method' => $request->payment_method,
        ]);

        $milestone = Milestone::find($request->milestone_id);
        if ($milestone) {
            $milestone->is_paid = true;
            $milestone->status = 'Completado'; 
            $milestone->save();
        }

        return redirect()->route('payments.index')->with('message', 'El pago se ha registrado y el hito ha sido actualizado');
    }

    public function show($id)
    {
        $payment = Payment::with('milestone')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'transaction_id' => 'required|unique:payments,transaction_id,' . $payment->id,
            'amount'         => 'required|numeric|min:1',
        ]);

        $payment->update($request->only(['transaction_id', 'amount']));

        return redirect()->route('payments.index')->with('message', 'Registro de pago actualizado correctamente');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        
        $milestone = Milestone::find($payment->milestone_id);
        if ($milestone) {
            $milestone->is_paid = false;
            $milestone->save();
        }

        $payment->delete();

        return redirect()->route('payments.index')->with('message', 'El registro de pago ha sido eliminado');
    }
}