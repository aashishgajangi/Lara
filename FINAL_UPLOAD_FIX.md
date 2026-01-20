# Final Upload Fix - PHP Configuration

**Issue:** `.user.ini` didn't work - need to edit PHP.ini directly

---

## Run These Commands:

```bash
# 1. Edit PHP.ini to increase upload limits
sudo sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 10M/' /etc/php/8.4/fpm/php.ini
sudo sed -i 's/post_max_size = 8M/post_max_size = 10M/' /etc/php/8.4/fpm/php.ini

# 2. Restart PHP-FPM
sudo systemctl restart php8.4-fpm

# 3. Verify changes
php -i | grep upload_max_filesize
# Should show: upload_max_filesize => 10M => 10M
```

---

## After Running Commands:

1. **Refresh browser** (Ctrl+Shift+R)
2. **Try uploading image**
3. **Should work now!**

---

## Alternative: Edit Manually

If commands don't work:

```bash
sudo nano /etc/php/8.4/fpm/php.ini

# Find and change:
upload_max_filesize = 10M
post_max_size = 10M

# Save (Ctrl+X, Y, Enter)
sudo systemctl restart php8.4-fpm
```

---

**This will fix the upload issue permanently!**
