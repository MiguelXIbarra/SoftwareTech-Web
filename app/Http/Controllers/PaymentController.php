<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Milestone;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Dentro de la clase ProjectController
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
        $this->validate($request, [
            'milestone_id' => 'required',
            'amount' => 'required|numeric',
            'transaction_id' => 'required|unique:payments',
            'payment_method' => 'required'
        ]);

        Payment::create($request->all());

        // Actualizar el estado del hito a pagado automáticamente
        $milestone = Milestone::find($request->milestone_id);
        $milestone->is_paid = true;
        $milestone->save();

        return redirect()->route('payments.index')->with('message', 'Pago registrado correctamente');
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
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
        $payment->update($request->all());
        return redirect()->route('payments.index')->with('message', 'Registro de pago actualizado');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->route('payments.index')->with('message', 'Registro de pago eliminado');
    }
}