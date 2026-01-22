<?php
date_default_timezone_set('Africa/Kinshasa');

function checkService($host, $port) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 0.5);
    if (is_resource($connection)) {
        fclose($connection);
        return true;
    }
    return false;
}

$services = [
    ['name' => 'Web Sentinel', 'host' => 'localhost', 'port' => 80, 'icon' => '🌐'],
    ['name' => 'Keycloak IAM', 'host' => 'keycloak', 'port' => 8080, 'icon' => '🔑'],
    ['name' => 'Oracle Core DB', 'host' => '10.1.2.3', 'port' => 1521, 'icon' => '🏛️'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rawbank | Sentinel Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans h-screen flex overflow-hidden">
    <aside class="w-72 bg-black text-white p-8 flex flex-col">
        <div class="mb-12">
            <h1 class="text-2xl font-black tracking-tighter">RAWBANK <span class="text-yellow-400">SENTINEL</span></h1>
            <p class="text-xs text-gray-400 mt-2 italic font-mono">Principal Architecture v4.0</p>
        </div>
        <nav class="flex-1">
            <div class="bg-yellow-400 text-black p-4 rounded-2xl font-bold flex items-center space-x-3 cursor-pointer shadow-lg shadow-yellow-400/20">
                <span>📊</span> <span>Status Dashboard</span>
            </div>
        </nav>
    </aside>

    <main class="flex-1 p-12 overflow-y-auto">
        <header class="flex justify-between items-center mb-12">
            <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Système de Surveillance</h2>
            <div class="bg-white px-6 py-2 rounded-full shadow-sm border border-gray-200 text-gray-500 font-mono">
                <?php echo date('d M Y | H:i:s'); ?>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach($services as $s): 
                $isUp = checkService($s['host'], $s['port']);
            ?>
            <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-gray-100 transition-all hover:scale-105">
                <div class="text-5xl mb-6"><?php echo $s['icon']; ?></div>
                <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo $s['name']; ?></h3>
                <div class="flex items-center space-x-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo $isUp ? 'bg-green-400' : 'bg-red-400'; ?> opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 <?php echo $isUp ? 'bg-green-500' : 'bg-red-500'; ?>"></span>
                    </span>
                    <span class="font-black <?php echo $isUp ? 'text-green-600' : 'text-red-600'; ?>">
                        <?php echo $isUp ? 'RUNNING' : 'UNREACHABLE'; ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>