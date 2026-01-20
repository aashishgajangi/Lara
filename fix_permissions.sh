#!/bin/bash

echo "Fixing permissions for Nginx access..."

# Allow others (www-data) to traverse the directory path
# They simply need 'execute' (+x) permission to pass through the folder
sudo chmod o+x /home/aashish
sudo chmod o+x /home/aashish/Code
sudo chmod o+x /home/aashish/Code/LaraCommerce

# Ensure storage is writable by www-data
sudo chown -R aashish:www-data /home/aashish/Code/LaraCommerce/storage
sudo chmod -R 775 /home/aashish/Code/LaraCommerce/storage

sudo chown -R aashish:www-data /home/aashish/Code/LaraCommerce/bootstrap/cache
sudo chmod -R 775 /home/aashish/Code/LaraCommerce/bootstrap/cache

echo "Permissions updated. You should be able to access the site now."
