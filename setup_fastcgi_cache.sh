#!/bin/bash

# Configuration
SITE_CONF="/etc/nginx/sites-available/nisargalahari.com"
CACHE_PATH="/var/run/nginx-cache"

# Ensure cache directory exists and permissions are correct
sudo mkdir -p $CACHE_PATH
sudo chown -R www-data:www-data $CACHE_PATH
sudo chmod 700 $CACHE_PATH

# 1. Add fastcgi_cache_path if not exists (Insert at line 1)
if ! grep -q "fastcgi_cache_path" $SITE_CONF; then
    sudo sed -i '1i fastcgi_cache_path /var/run/nginx-cache levels=1:2 keys_zone=LARAVEL:100m inactive=60m;' $SITE_CONF
fi

# 2. Add Cache Configurations inside location ~ \.php$ block
# We use a temp file to construct the new block content
TEMP_BLOCK=$(mktemp)
cat <<EOF > $TEMP_BLOCK
    location ~ \.php$ {
        # FastCGI Cache Config
        set \$skip_cache 0;
        if (\$request_method = POST) { set \$skip_cache 1; }
        if (\$query_string != "") { set \$skip_cache 1; }
        if (\$http_cookie ~* "nginx_bypass") { set \$skip_cache 1; }
        
        fastcgi_cache_bypass \$skip_cache;
        fastcgi_no_cache \$skip_cache;
        
        fastcgi_cache LARAVEL;
        fastcgi_cache_valid 200 60m;
        fastcgi_cache_use_stale error timeout updating invalid_header http_500;
        fastcgi_cache_lock on;
        add_header X-FastCGI-Cache \$upstream_cache_status;

        fastcgi_pass unix:/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }
EOF

# Use sed to replace the existing PHP block with our optimized one
# We look for the block starting with "location ~ \.php$ {" and ending with "}"
# Note: This is a simple replacements assuming standard formatting.
# If it fails, we fall back to manual instructions.

# Backup first
sudo cp $SITE_CONF "${SITE_CONF}.bak"

# Replace the block (Complex because it spans lines. We will overwrite the file content constructed safely)
# Actually, since we know the file structure from 'cat', we can just use a specialized replacement or overwrite if we are confident.
# But being safe:
# We will just append the cache instructions if we can find the fastcgi_pass line.

if grep -q "fastcgi_cache LARAVEL" $SITE_CONF; then
    echo "Cache already configured."
else
    # Insert cache rules before fastcgi_pass
    sudo sed -i '/fastcgi_pass/i \        set $skip_cache 0;\n        if ($request_method = POST) { set $skip_cache 1; }\n        if ($query_string != "") { set $skip_cache 1; }\n        if ($http_cookie ~* "nginx_bypass") { set $skip_cache 1; }\n        fastcgi_cache_bypass $skip_cache;\n        fastcgi_no_cache $skip_cache;\n        fastcgi_cache LARAVEL;\n        fastcgi_cache_valid 200 60m;\n        fastcgi_cache_use_stale error timeout updating invalid_header http_500;\n        fastcgi_cache_lock on;\n        add_header X-FastCGI-Cache $upstream_cache_status;' $SITE_CONF
fi

# Test and Reload
sudo nginx -t && sudo systemctl reload nginx
echo "Nginx Cache Configured!"
