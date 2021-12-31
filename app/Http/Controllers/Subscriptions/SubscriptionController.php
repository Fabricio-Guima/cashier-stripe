<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    
    public function checkout(Request $request)
    {
        if(auth()->user()->subscribed('default')) {
            return redirect()->route('subscriptions.premium');
        }
        //passar a intenção de pagamento 
        return view('subscriptions.checkout', 
        ['intent' => auth()->user()->createSetupIntent(),
    'plan' => session('plan')]);
    }

    public function store(Request $request)
    {
        //criando assinatura para o usuário de 100 conto
        //label ==default, segundo parametro é o produto do stripe
        //passar o token do cartao
        //isso cria a assinatura de fato
        $plan =  session('plan');
        
        $request->user()->newSubscription('default', $plan->stripe_id)
        ->create($request->token);

        return redirect()->route('subscriptions.premium');
    }

    public function premium(Request $request)
    {
        return view('subscriptions.premium');
    }

    public function account(Request $request)
    {
        $invoices = auth()->user()->invoices();
        return view('subscriptions.account',['invoices' => $invoices]);
    }

    public function downloadInvoice($invoiceId)
    {
       
        return auth()->user()->downloadInvoice($invoiceId, [
            'vendor' => config('app.name'),
            'product' => 'Assinatura VIP'
        ]);
    }

    //cancelar assinatura
    public function cancel(Request $request)
    {
        // assinei dia 01/01 e expira 01/02, mas posso cancelar a assinatura dia 05/01, por exemplo e continuar usando até 01/02 e após esse período, não serei mais cobrado
       auth()->user()->subscription('default')->cancel();         
        
        return redirect()->route('subscriptions.account');
    }

    //reativar assinatura (caso vc tenha cancelado e se arrependeu e quer usar a assinatura novamente)
    public function resume(Request $request)
    {
       
       auth()->user()->subscription('default')->resume();         
        
        return redirect()->route('subscriptions.account');
    }
}
