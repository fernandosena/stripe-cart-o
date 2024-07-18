## Altere o arquivo .env.example para .env e adicione as suas credêciais

# instale o strip cli: https://docs.stripe.com/stripe-cli?locale=pt
### webhook local: https://docs.stripe.com/webhooks
stripe listen --forward-to 127.0.0.1:8080/webhook.php

## Copia o codigo whsec_... e condigure o .env

# Instalando as depedências webhook PHP
### Tenha o PHP e o Composer Instalado
cd php
composer install
php -S 127.0.0.1:8080

# rodar aplicação
cd flask
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt

export FLASK_APP=main.py
python3 -m flask run --port=4242



