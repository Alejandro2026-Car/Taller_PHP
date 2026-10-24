<?php
class Acronimo
{
    public static function generar(string $texto): string
    {
        $textoLimpio = preg_replace('/[^\w\s]/u', '', str_replace('-', ' ', $texto));
        $palabras = explode(' ', $textoLimpio);
        $acronimo = '';

        foreach ($palabras as $palabra) {
            $palabra = trim($palabra);
            if (!empty($palabra)) {
                $acronimo .= mb_strtoupper(mb_substr($palabra, 0, 1));
            }
        }
        return $acronimo;
    }
}

$resultado = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['frase'])) {
    $resultado = Acronimo::generar($_POST['frase']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Punto 1 - Acrónimo</title>
    <link rel="stylesheet" href="../Vista/Punto1.css">
</head>

<body>
    <div class="capa-desenfoque">
        <h1>Acrónimos</h1>
    </div>
    <div class="container">
        <h2>1. Convertir una frase en su acrónimo</h2>

        <!-- Al dejar action="" se envía al mismo archivo y mantiene el resultado abajo -->
        <form action="" method="POST">
            <label for="frase">Ingrese la frase:</label>
            <input type="text" id="frase" name="frase" value="<?= htmlspecialchars($_POST['frase'] ?? '') ?>" required placeholder="Ej: Portable Network Graphics">
            <div class="container">
                <button type="submit">Generar Acrónimo</button>
            </div>
            <div class="container">
                <button type="button">
                    <a href="../index.php">Regresar</a>
                </button>
            </div>
        </form>

    </div>
    <div class="container">
        <div class="resultado-box">
            <?php if ($resultado !== null): ?>
                <h3>Resultado:</h3>
                <p><strong>Acrónimo:</strong> <?= htmlspecialchars($resultado) ?></p>
            <?php endif; ?>

        </div>
    </div>
</body>

</html>