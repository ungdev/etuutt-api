if [ ! -d /var/www/html/vendor ]; then
    echo "[INFO] Vendor folder not present - installing dependencies"
    cd /var/www/html
    composer install
    echo "[INFO] Dependencies installed"
else
    echo "[INFO] Vendor folder already present, nothing to do."
fi