document.addEventListener('DOMContentLoaded', async () => {
  const {publishableKey} = await fetch('/config').then((r) => r.json());
  if (!publishableKey) {
    addMessage(
      'Nenhuma chave publicável retornada do servidor. Por favor, verifique `.env` e tente novamente'
    );
    alert('Defina sua chave de API publicável do Stripe no arquivo .env');
  }

  const stripe = Stripe(publishableKey);

  const elements = stripe.elements();
  const card = elements.create('card');
  card.mount('#card-element');

  const form = document.getElementById('payment-form');
  let submitted = false;
  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if(submitted) { return; }
    submitted = true;
    form.querySelector('button').disabled = true;

    const {error: backendError, clientSecret} = await fetch(
      '/create-payment-intent',
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        }
      }
    ).then((r) => r.json());

    if (backendError) {
      addMessage(backendError.message);

      submitted = false;
      form.querySelector('button').disabled = false;
      return;
    }

    addMessage(`Código do cliente gerado.`);

    const nameInput = document.querySelector('#name');

    const {error: stripeError, paymentIntent} = await stripe.confirmCardPayment(
      clientSecret,
      {
        payment_method: {
          card: card,
          billing_details: {
            name: nameInput.value,
          },
        },
      }
    );

    if (stripeError) {
      addMessage(stripeError.message);

      // reenable the form.
      submitted = false;
      form.querySelector('button').disabled = false;
      return;
    }

    addMessage(`Pagamento ${paymentIntent.status}: ${paymentIntent.id}`);
  });
});
