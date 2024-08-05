import stripe
import os
import json
from flask import Flask, jsonify, request
from dotenv import load_dotenv, find_dotenv

load_dotenv(find_dotenv())
stripe.api_key = os.getenv('STRIPE_SECRET_KEY')

app = Flask(__name__, static_url_path="")

# Retorna os dados das configurações
@app.route('/config', methods=['GET'])
def get_config():
    response = jsonify({'publishableKey': os.getenv('STRIPE_PUBLISHABLE_KEY')})
    return response 

# função para criar customer (cliente)
# recomendavel criar usuário ao se cadastrar no sistema
# https://docs.stripe.com/api/customers/object
def create_customer(
        name = "Jenny Rosen", 
        email = "jennyrosen@example.com", 
        phone = "11999999999",
        city = "São Paulo",
        country = "BR",
        line1 = "Rua Paulista",
        line2 = "Apartamento 2",
        postal_code = "01310-100",
        state = "São Paulo"
    ):
    custorm = stripe.Customer.create(
        name=name,
        phone=phone,
        email=email,
        description="cliente 1",
        address={
            "city": city,
            "country": country,
            "line1": line1,
            "line2": line2,
            "postal_code": postal_code,
            "state": state,
        },
        shipping={
            "address": {
                "city": city,
                "country": country,
                "line1": line1,
                "line2": line2,
                "postal_code": postal_code,
                "state": state,
            },
            "name": name,
            "phone": phone,
        }
    )

    return custorm.id
    
# criar um produto
# https://docs.stripe.com/api/products/create
def product_create(name="Gold Plan"):
    product = stripe.Product.create(
        name=name,
        description="Descrição do produto",
    )
    return product.id

# criar um valor para o produto
# https://docs.stripe.com/api/prices/create
def price_create(product, name = "Gold Plan", amount = 1000, interval = {"interval": "month"}):
    price = stripe.Price.create(
        currency="brl",
        unit_amount=amount,
        recurring=interval,
        product = product,
    )
    return price.id

@app.route('/payment-cart', methods=['POST'])
def create_payment_cart():
    data = request.get_json()
    
    #cria o usuario no stripe
    id_custorm = create_customer(
        name=data["name"],
        email=data["email"],
        phone=data["phone"],
        city=data["city"],
        country=data["country"],
        line1=data["address"],
        line2=data["address2"],
        postal_code=data["zip"],
        state=data["state"],
    )

    #salva o pagamento para futuras compras
    setup_intent = stripe.SetupIntent.create(
        payment_method_types=["card"],
        customer=id_custorm
    )
    
    #retorna os dados para gerar o cookie
    return jsonify({
        'type': 'setup',
        'clientSecret': setup_intent.client_secret,
        'custorm': id_custorm,
    })

@app.route('/create-payment', methods=['POST'])
def create_payment():
    data = json.loads(request.get_json())


    # recupera o ID dado do cartão
    cards = stripe.PaymentMethod.list(
        customer=data["custorm"],
        type="card",
    )

    plan_list = []
    for item in data["products"]:
        product = product_create(name=item["nome"]) #cria os produtos no stripe

        #cria um dicionario com os dados dos planos
        if item["tipo"] == "plan":
            amount = int(item["preco"])*100
            price = price_create(product=product, name=item["nome"], amount=amount)
            plan_list.append({
                'price': price,
                'quantity': int(item["quantidade"]),
            })
        else:
            #cria os pagamentos para cada produto
            amount = (int(item["preco"])*100)*int(item["quantidade"])
            try:
                payment = stripe.PaymentIntent.create(
                    amount=amount,
                    currency='brl',
                    automatic_payment_methods={"enabled": True},
                    customer=data["custorm"],
                    payment_method=cards.data[0].id,
                    return_url="https://127.0.0.1/aaa",
                    off_session=True,
                    confirm=True,
                    metadata={"id_internal": item["id"]}
                )
                print(f"Pagamento produto realizado. ID: {payment.id}")
            except stripe.error.CardError as e:
                print("Erro ao pagar produtos")

        # realiza a assinatura
        for itens in plan_list:
            try:
                subscription = stripe.Subscription.create(
                    customer=data["custorm"],
                    items=[itens],
                    default_payment_method=cards.data[0].id
                )
                print(f"Assinatura realizada. ID: {subscription.id}")
            except stripe.error.CardError as e:
                print("Erro ao pagar assinatura")
        
    return jsonify({'stop': True})

# Permite acessar o Flask por outra url e porta
@app.after_request
def after_request(response):
  response.headers['Access-Control-Allow-Origin'] = '*'
  response.headers['Access-Control-Allow-Methods'] = 'POST'
  response.headers['Access-Control-Allow-Headers'] = 'Content-Type'
  return response

if __name__ == '__main__':
    app.run(port=4242, debug=True)
