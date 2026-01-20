#!/bin/bash
# Optimize Nginx Configuration

SITE_CONF="/etc/nginx/sites-available/nisargalahari.com"

# Check if file exists
if [ ! -f "$SITE_CONF" ]; then
    echo "Config file not found!"
    exit 1
fi

# Add Gzip and Cache Headers
# We use sed to insert these settings inside the location / block and add a new location block
# This is a bit fragile with sed, but effective for this specific file structure.

# Enable Gzip in location /
sudo sed -i '/try_files \$uri \$uri\/ \/index.php?\$query_string;/a \        gzip on;\n        gzip_types text/plain text/css application/json application/javascript text/xml application/xml application/xml+rss text/javascript;' $SITE_CONF

# Add Cache for static assets before the end of the file (before the last closing brace)
# We assume the file ends with a closing brace for the server block.
# We insert before the last line.
sudo sed -i '$i \
    location ~* \\.(jpg|jpeg|png|gif|ico|css|js)$ {\
        expires 30d;\
        add_header Cache-Control "public, no-transform";\
    }' $SITE_CONF

echo "Nginx configuration updated."
echo "Testing Nginx configuration..."
sudo nginx -t

if [ $? -eq 0 ]; then
    echo "Reloading Nginx..."
    sudo systemctl reload nginx
    echo "Done!"
else
    echo "Nginx configuration test failed. Please check $SITE_CONF"
fi
