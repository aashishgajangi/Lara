#!/bin/bash

# Define variables
DOMAIN="nisargalahari.com"
WWW_DOMAIN="www.nisargalahari.com"
ROOT_DIR="/var/www/LaraCommerce/public"
NGINX_CONF="/etc/nginx/sites-available/$DOMAIN"
PHP_SOCK="/run/php/php8.4-fpm.sock"

# Check for sudo
if [ "$EUID" -ne 0 ]; then 
  echo "Please run as root (sudo bash setup_nisargalahari.sh)"
  exit
fi

echo "Creating Nginx configuration for $WWW_DOMAIN..."

cat > "$NGINX_CONF" <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name $DOMAIN;
    return 301 https://$WWW_DOMAIN\$request_uri;
}

server {
    listen 80;
    listen [::]:80;
    server_name $WWW_DOMAIN;
    root $ROOT_DIR;

    # Cloudflare Header Handling
    set_real_ip_from 103.21.244.0/22;
    set_real_ip_from 103.22.200.0/22;
    set_real_ip_from 103.31.4.0/22;
    set_real_ip_from 104.16.0.0/13;
    set_real_ip_from 104.24.0.0/14;
    set_real_ip_from 108.162.192.0/18;
    set_real_ip_from 131.0.72.0/22;
    set_real_ip_from 141.101.64.0/18;
    set_real_ip_from 162.158.0.0/15;
    set_real_ip_from 172.64.0.0/13;
    set_real_ip_from 173.245.48.0/20;
    set_real_ip_from 188.114.96.0/20;
    set_real_ip_from 190.93.240.0/20;
    set_real_ip_from 197.234.240.0/22;
    set_real_ip_from 198.41.128.0/17;
    real_ip_header CF-Connecting-IP;

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

echo "Testing Nginx configuration..."
nginx -t

echo "Reloading Nginx..."
systemctl reload nginx

echo "Done! Site should be configured for http://$WWW_DOMAIN (Cloudflare handles SSL)"
