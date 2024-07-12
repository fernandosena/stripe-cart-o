import stripe
import os

from flask import Flask, render_template, jsonify
from dotenv import load_dotenv, find_dotenv

load_dotenv(find_dotenv())
stripe.api_key = os.getenv('STRIPE_SECRET_KEY')

static_dir = str(os.path.abspath(os.path.join(__file__ , "..", os.getenv("STATIC_DIR"))))
app = Flask(__name__, static_folder=static_dir, static_url_path="", template_folder=static_dir)

@app.route('/config', methods=['GET'])
def get_config():
    return jsonify({'publishableKey': os.getenv('STRIPE_PUBLISHABLE_KEY')})

@app.route('/create-payment-intent', methods=['POST'])
def create_payment():
    # data = json.loads(request.data)

    currency = "brl"
    orderAmount = 15000 #valor em centavos
    params: dict[str, any]
    
    params = {
        'payment_method_types': [
            'card'
        ],
        'amount': orderAmount,
        'currency': currency
    }

    try:
        intent = stripe.PaymentIntent.create(**params)
        return jsonify({'clientSecret': intent.client_secret})
    except stripe.error.StripeError as e:
        return jsonify({'error': {'message': str(e)}}), 400
    except Exception as e:
        return jsonify({'error': {'message': str(e)}}), 400
    
if __name__ == '__main__':
    app.run(port=4242, debug=True)
