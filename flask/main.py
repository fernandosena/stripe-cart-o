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
def price_create(product, name = "Gold Plan", amount = 1000, interval = "month"):
    price = stripe.Price.create(
        currency="brl",
        unit_amount=amount,
        recurring={"interval": interval},
        product = product,
    )
    return price.id

# Realiza a cobrança pelo cartão
# https://docs.stripe.com/api/payment_intents/create
@app.route('/create-payment-intent', methods=['POST'])
def create_payment():
    data = json.loads(request.data)
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
    params: dict[str, any]
    
    params = {
        'payment_method_types': [
            'card'
        ],
        'amount': 15000, #valor em centavos
        'currency': "brl",
        'customer': id_custorm,
        'description': "Descrição do pagamento",
    }

    try:
        intent = stripe.PaymentIntent.create(**params)
        response = jsonify({'clientSecret': intent.client_secret})
        return response
    except stripe.error.StripeError as e:
        response = jsonify({'error': {'message': str(e)}}), 400
        return response
    except Exception as e:
        response = jsonify({'error': {'message': str(e)}}), 400
        return response

# Cria uma assinatura recorrente
# https://docs.stripe.com/api/subscriptions/create
@app.route('/create-subscription', methods=['POST'])
def create_subscription():
    # para se criar uma assinatura precisa-se de um cliente, produto e valor ja cadastrado, 
    data = json.loads(request.data)
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
    ) # cria um cliente

    product = product_create(name="Produto exemplo") #criar o o produto
    price = price_create(product=product, name="Valor Teste") #criar o valor e anex ao produto
    data = json.loads(request.data)

    try:
        subscription = stripe.Subscription.create(
            customer=id_custorm,
            items=[{
                'price': price,
            }],
            payment_behavior='default_incomplete',
            payment_settings={'save_default_payment_method': 'on_subscription'},
            expand=['latest_invoice.payment_intent'],
        )
        response = jsonify(subscriptionId=subscription.id, clientSecret=subscription.latest_invoice.payment_intent.client_secret)
        return response
    except stripe.error.StripeError as e:
        response = jsonify({'error': {'message': str(e)}}), 400
        return response
    except Exception as e:
        response = jsonify({'error': {'message': str(e)}}), 400
        return response

# Permite acessar o Flask por outra url e porta
@app.after_request
def after_request(response):
  response.headers['Access-Control-Allow-Origin'] = '*'
  response.headers['Access-Control-Allow-Methods'] = 'POST'
  response.headers['Access-Control-Allow-Headers'] = 'Content-Type'
  return response

if __name__ == '__main__':
    app.run(port=4242, debug=True)
