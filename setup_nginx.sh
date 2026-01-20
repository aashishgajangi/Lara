#!/bin/bash

# Define variables
DOMAIN="laracommerce.com"
ROOT_DIR="/home/aashish/Code/LaraCommerce/public"
NGINX_CONF="/etc/nginx/sites-available/$DOMAIN"
PHP_SOCK="/run/php/php8.4-fpm.sock"

# Check for sudo
if [ "$EUID" -ne 0 ]; then 
  echo "Please run as root (sudo bash setup_nginx.sh)"
  exit
fi

echo "Creating Nginx configuration for $DOMAIN..."

cat > "$NGINX_CONF" <<EOF
server {
    listen 80;
    server_name $DOMAIN;
    root $ROOT_DIR;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:$PHP_SOCK;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

echo "Enabling site..."
ln -sf "$NGINX_CONF" "/etc/nginx/sites-enabled/$DOMAIN"

echo "Updating /etc/hosts..."
if ! grep -q "$DOMAIN" /etc/hosts; then
    echo "127.0.0.1 $DOMAIN" >> /etc/hosts
fi

echo "Testing Nginx configuration..."
nginx -t

echo "Reloading Nginx..."
systemctl reload nginx

echo "Done! Site should be available at http://$DOMAIN"
