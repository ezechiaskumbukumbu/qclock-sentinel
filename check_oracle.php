<?php
// Script de diagnostic OCI8 - QClock Sentinel
echo "[DEBUG] Checking Oracle OCI8 Extension...\n";

if (extension_loaded('oci8')) {
    echo "[SUCCESS] Extension OCI8 est chargée.\n";
    echo "[INFO] Client Version: " . oci_client_version() . "\n";
} else {
    echo "[ERROR] Extension OCI8 NON TROUVÉE.\n";
    exit(1);
}

// Test de l'environnement
echo "[INFO] LD_LIBRARY_PATH: " . getenv('LD_LIBRARY_PATH') . "\n";
?>