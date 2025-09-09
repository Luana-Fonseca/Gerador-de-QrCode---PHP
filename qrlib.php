<?php
/**
 * PHP QR Code encoder
 * Simplified version for standalone use
 */

class QRcode {
    
    const QR_ECLEVEL_L = 0;
    const QR_ECLEVEL_M = 1;
    const QR_ECLEVEL_Q = 2;
    const QR_ECLEVEL_H = 3;
    
    public static function png($text, $outfile = false, $level = self::QR_ECLEVEL_L, $size = 3, $margin = 4) {
        $enc = new QRencode();
        return $enc->encodePNG($text, $outfile, $level, $size, $margin);
    }
}

class QRencode {
    
    public function encodePNG($text, $outfile, $level, $size, $margin) {
        $tab = $this->encode($text, $level);
        $image = $this->createImage($tab, $size, $margin);
        
        if ($outfile === false) {
            header('Content-Type: image/png');
            imagepng($image);
        } else {
            imagepng($image, $outfile);
        }
        
        imagedestroy($image);
    }
    
    private function encode($text, $level) {
        // Simulação simples de geração de QR code
        // Em uma implementação real, aqui estaria a lógica completa de codificação
        $version = 1;
        $data = $this->prepareData($text, $level, $version);
        return $this->createMatrix($data, $version);
    }
    
    private function prepareData($text, $level, $version) {
        // Simulação - em implementação real, aqui estaria a codificação real dos dados
        $data = [];
        $len = strlen($text);
        for ($i = 0; $i < $len; $i++) {
            $data[] = ord($text[$i]);
        }
        return $data;
    }
    
    private function createMatrix($data, $version) {
        // Matriz básica de exemplo (21x21 para versão 1)
        $size = 21;
        $matrix = array_fill(0, $size, array_fill(0, $size, 0));
        
        // Padrão de finder (cantos)
        $this->addFinderPattern($matrix, 0, 0);
        $this->addFinderPattern($matrix, 0, $size - 7);
        $this->addFinderPattern($matrix, $size - 7, 0);
        
        // Padrão de alinhamento (centro)
        $this->addAlignmentPattern($matrix, $size / 2, $size / 2);
        
        // Adiciona alguns dados de exemplo
        $this->addSampleData($matrix, $data);
        
        return $matrix;
    }
    
    private function addFinderPattern(&$matrix, $x, $y) {
        for ($i = 0; $i < 7; $i++) {
            for ($j = 0; $j < 7; $j++) {
                if ($i == 0 || $i == 6 || $j == 0 || $j == 6 || ($i >= 2 && $i <= 4 && $j >= 2 && $j <= 4)) {
                    if ($x + $i < count($matrix) && $y + $j < count($matrix[0])) {
                        $matrix[$x + $i][$y + $j] = 1;
                    }
                }
            }
        }
    }
    
    private function addAlignmentPattern(&$matrix, $x, $y) {
        for ($i = -2; $i <= 2; $i++) {
            for ($j = -2; $j <= 2; $j++) {
                if (abs($i) == 2 || abs($j) == 2 || ($i == 0 && $j == 0)) {
                    $posX = $x + $i;
                    $posY = $y + $j;
                    if ($posX >= 0 && $posX < count($matrix) && $posY >= 0 && $posY < count($matrix[0])) {
                        $matrix[$posX][$posY] = 1;
                    }
                }
            }
        }
    }
    
    private function addSampleData(&$matrix, $data) {
        $size = count($matrix);
        $dataIndex = 0;
        $dataLength = count($data);
        
        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                // Pula os padrões já definidos
                if ($matrix[$i][$j] == 0 && $dataIndex < $dataLength) {
                    $matrix[$i][$j] = ($data[$dataIndex] % 2) + 1;
                    $dataIndex++;
                }
            }
        }
    }
    
    private function createImage($matrix, $size, $margin) {
        $matrixSize = count($matrix);
        $imageSize = $matrixSize * $size + 2 * $margin;
        
        $image = imagecreate($imageSize, $imageSize);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        
        imagefill($image, 0, 0, $white);
        
        for ($i = 0; $i < $matrixSize; $i++) {
            for ($j = 0; $j < $matrixSize; $j++) {
                if ($matrix[$i][$j] == 1) {
                    $x = $margin + $j * $size;
                    $y = $margin + $i * $size;
                    imagefilledrectangle($image, $x, $y, $x + $size - 1, $y + $size - 1, $black);
                }
            }
        }
        
        return $image;
    }
}

// Função auxiliar para uso simples
function generateQRCode($text, $filename = false, $size = 10, $margin = 4, $level = QRcode::QR_ECLEVEL_L) {
    QRcode::png($text, $filename, $level, $size, $margin);
}
?>