
document.addEventListener('DOMContentLoaded', async () => {
  const {publishableKey} = await fetch(url+'/config').then((r) => r.json());
  if (!publishableKey) {
    alert('Defina sua chave de API publicável do Stripe no arquivo .env');
  }

  const stripe = Stripe(publishableKey);

  const options = {
    mode: 'subscription',
    amount: 1099,
    currency: 'brl',
    // Fully customizable with appearance API.
    appearance: {/*...*/},
  };

  const elements = stripe.elements(options);
  const card = elements.create('payment');
  card.mount('#payment-element');

  const form = document.getElementById('payment-form');
  const submitBtn = document.getElementById('submit');

  const handleError = (error) => {
    const messageContainer = document.querySelector('#error-message');
    messageContainer.textContent = error.message;
    submitBtn.disabled = false;
  }

  function setCookie(name, value, hours) {
    let expires = "";
    if (hours) {
      const date = new Date();
      date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/"; 
  
  }

  
  form.addEventListener('submit', async (event) => {
    // We don't want to let default form submission happen here,
    // which would refresh the page.
    event.preventDefault();

    // Prevent multiple form submissions
    if (submitBtn.disabled) {
      return;
    }

    // Disable form submission while loading
    submitBtn.disabled = true;

    // Trigger form validation and wallet collection
    const {error: submitError} = await elements.submit();
    if (submitError) {
      handleError(submitError);
      return;
    }

    // Create the subscription

    const data = { 
      products: dadosCarrinho,
      
      name: document.querySelector('#name').value,
      email: document.querySelector('#email').value,
      phone: document.querySelector('#phone').value,
      address: document.querySelector('#address').value,
      address2: document.querySelector('#address2').value,
      country: document.querySelector('#country').value,
      city: document.querySelector('#city').value,
      state: document.querySelector('#state').value,
      zip: document.querySelector('#zip').value,
    };

    const res = await fetch(url+'/payment-cart', 
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });
    const {type, clientSecret, custorm} = await res.json();
    const confirmIntent = type === "setup" ? stripe.confirmSetup : stripe.confirmPayment;
    
    const cartData = {
      custorm: custorm,
      products: dadosCarrinho
    };  
    const cartString = JSON.stringify(cartData);

  
    document.cookie = `cart=${cartString}; path=/`;


    // Confirm the Intent using the details collected by the Payment Element
    const {error} = await confirmIntent({
      elements,
      clientSecret,
      confirmParams: {
        return_url: url2+'/success.php',
      },
    });

    if (error) {
      // This point is only reached if there's an immediate error when confirming the Intent.
      // Show the error to your customer (for example, "payment details incomplete").
      alert(error);
    } else {
      alert(`Assinatura realizado com sucesso`);
      // Your customer is redirected to your `return_url`. For some payment
      // methods like iDEAL, your customer is redirected to an intermediate
      // site first to authorize the payment, then redirected to the `return_url`.
    }
  });

});
