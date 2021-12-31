<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div id="show-errors" style="display: none;" class="mt-2 text-sm text-red-600"></div>

                    <p>Assinando : <b>{{ $plan->name }}</b></p>
					 <p>Preço: <b>R$ {{ $plan->price_br }}</b></p>
                    <form action="{{ route('subscriptions.store') }}" method="post" id="form">
                        @csrf						

                        <div class="col-span-6 sm:col-span-4 py-2">
                            <input type="text" name="card-holder-name" id="card-holder-name" placeholder="Nome no cartão" class="w-full px-3 py-2 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-indigo-100 focus:border-indigo-300 dark:bg-gray-700 dark:text-white dark:placeholder-gray-500 dark:border-gray-600 dark:focus:ring-gray-900 dark:focus:border-gray-500">
                        </div>

                        <div class="col-span-6 sm:col-span-4 py-2">
                            <div id="card-element"></div>
                        </div>

                        <div class="col-span-6 sm:col-span-4 py-2">
                            <button id="card-buttom" data-secret="{{ $intent->client_secret }}" type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Enviar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
const stripe = Stripe("{{config('cashier.key')}}")
const elements = stripe.elements()
const cardElement = elements.create('card')
cardElement.mount('#card-element')

//pegar dados de pagamento
const form = document.querySelector('#form');
const cardHolderName = document.querySelector('#card-hold-name');
const cardButton = document.querySelector('#card-button');
const clientSecret = cardButton.dataset.secret

form.addEventListener('submit', async (event) => {
	event.preventDefault()

	console.log(cardHolderName.value)
	console.log(clientSecret)

	const { setupIntent, error } = await stripe.confirmCardSetup(
        clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: cardHolderName.value
                }
            }
        }
    );

	if(error) {
		console.log(error.message)
		return 
	}

	let token = document.createElement('input')
	token.setAttribute('type', 'hidden')
	token.setAttribute('name', 'token')
	token.setAttribute('value',setupIntent.payment_method)
	form.appendChild(token)

	//deu certo 
	console.log(setupIntent)

	form.submit()

	

})

</script>
