<?php
require_once 'qrlib.php';

// Configurações
$defaultText = "https://www.exemplo.com";
$defaultSize = 10;
$defaultMargin = 4;

// Processa o formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = isset($_POST['text']) ? trim($_POST['text']) : $defaultText;
    $size = isset($_POST['size']) ? intval($_POST['size']) : $defaultSize;
    $margin = isset($_POST['margin']) ? intval($_POST['margin']) : $defaultMargin;
    
    // Validações
    if (empty($text)) {
        $text = $defaultText;
    }
    
    if ($size < 1 || $size > 20) {
        $size = $defaultSize;
    }
    
    if ($margin < 0 || $margin > 10) {
        $margin = $defaultMargin;
    }
} else {
    $text = $defaultText;
    $size = $defaultSize;
    $margin = $defaultMargin;
}

// Se for para mostrar a imagem
if (isset($_GET['show']) && $_GET['show'] === '1') {
    $text = isset($_GET['text']) ? urldecode($_GET['text']) : $defaultText;
    $size = isset($_GET['size']) ? intval($_GET['size']) : $defaultSize;
    $margin = isset($_GET['margin']) ? intval($_GET['margin']) : $defaultMargin;
    
    QRcode::png($text, false, QRcode::QR_ECLEVEL_L, $size, $margin);
    exit;
}

// Se for para download
if (isset($_GET['download']) && $_GET['download'] === '1') {
    $text = isset($_GET['text']) ? urldecode($_GET['text']) : $defaultText;
    $size = isset($_GET['size']) ? intval($_GET['size']) : $defaultSize;
    $margin = isset($_GET['margin']) ? intval($_GET['margin']) : $defaultMargin;
    
    header('Content-Type: image/png');
    header('Content-Disposition: attachment; filename="qrcode.png"');
    QRcode::png($text, false, QRcode::QR_ECLEVEL_L, $size, $margin);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de QR Code</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            font-weight: bold;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        .qr-result {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
        }
        
        .qr-image {
            background: white;
            padding: 15px;
            border-radius: 10px;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        
        .qr-image img {
            display: block;
            max-width: 250px;
            height: auto;
        }
        
        .qr-actions {
            margin: 20px 0;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .btn-download {
            background: #17a2b8;
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        .qr-info {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: left;
        }
        
        .qr-info p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        
        .qr-info strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎯 Gerador de QR Code</h1>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="text">Texto ou URL:</label>
                <input type="text" id="text" name="text" value="<?php echo htmlspecialchars($text); ?>" 
                       placeholder="Digite o texto ou URL..." required>
            </div>
            
            <div class="form-group">
                <label for="size">Tamanho do QR Code (1-20):</label>
                <input type="number" id="size" name="size" value="<?php echo $size; ?>" 
                       min="1" max="20" placeholder="Tamanho">
            </div>
            
            <div class="form-group">
                <label for="margin">Margem (0-10):</label>
                <input type="number" id="margin" name="margin" value="<?php echo $margin; ?>" 
                       min="0" max="10" placeholder="Margem">
            </div>
            
            <button type="submit">✨ Gerar QR Code</button>
        </form>
        
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <div class="qr-result">
            <h2>✅ Seu QR Code foi gerado!</h2>
            
            <div class="qr-image">
                <img src="?show=1&text=<?php echo urlencode($text); ?>&size=<?php echo $size; ?>&margin=<?php echo $margin; ?>" 
                     alt="QR Code gerado">
            </div>
            
            <div class="qr-actions">
                <a href="?download=1&text=<?php echo urlencode($text); ?>&size=<?php echo $size; ?>&margin=<?php echo $margin; ?>" 
                   class="btn btn-download">📥 Download PNG</a>
                <a href="?" class="btn">🔄 Gerar Novo</a>
            </div>
            
            <div class="qr-info">
                <p><strong>📋 Conteúdo:</strong> <?php echo htmlspecialchars($text); ?></p>
                <p><strong>📏 Tamanho:</strong> <?php echo $size; ?> pixels</p>
                <p><strong>📐 Margem:</strong> <?php echo $margin; ?> pixels</p>
                <p><strong>🛡️ Nível de correção:</strong> L (7%)</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>