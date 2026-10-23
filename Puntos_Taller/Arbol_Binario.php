<?php
class NodoArbol
{
    public string $valor;
    public ?NodoArbol $izq = null;
    public ?NodoArbol $der = null;

    public function __construct(string $valor)
    {
        $this->valor = trim($valor);
    }
}

class ConstructorArbol
{
    public static function desdePreIn(array $pre, array $in): ?NodoArbol
    {
        if (empty($pre) || empty($in)) return null;

        $raizValor = $pre[0];
        $raiz = new NodoArbol($raizValor);
        $idxRaiz = array_search($raizValor, $in);

        if ($idxRaiz === false) return null;

        $inIzq = array_slice($in, 0, $idxRaiz);
        $inDer = array_slice($in, $idxRaiz + 1);

        $preIzq = array_slice($pre, 1, count($inIzq));
        $preDer = array_slice($pre, 1 + count($inIzq));

        $raiz->izq = self::desdePreIn($preIzq, $inIzq);
        $raiz->der = self::desdePreIn($preDer, $inDer);

        return $raiz;
    }

    public static function desdeInPost(array $in, array $post): ?NodoArbol
    {
        if (empty($in) || empty($post)) return null;

        $raizValor = end($post);
        $raiz = new NodoArbol($raizValor);
        $idxRaiz = array_search($raizValor, $in);

        if ($idxRaiz === false) return null;

        $inIzq = array_slice($in, 0, $idxRaiz);
        $inDer = array_slice($in, $idxRaiz + 1);

        $postIzq = array_slice($post, 0, count($inIzq));
        $postDer = array_slice($post, count($inIzq), count($inDer));

        $raiz->izq = self::desdeInPost($inIzq, $postIzq);
        $raiz->der = self::desdeInPost($inDer, $postDer);

        return $raiz;
    }

    // Imprimir de forma visual en texto plano
    public static function imprimirTexto(?NodoArbol $nodo, string $prefijo = '', bool $esIzquierdo = true): string
    {
        if ($nodo === null) return '';

        $salida = $prefijo . ($esIzquierdo ? '├── ' : '└── ') . $nodo->valor . "\n";
        $nuevoPrefijo = $prefijo . ($esIzquierdo ? '│   ' : '    ');

        $salida .= self::imprimirTexto($nodo->izq, $nuevoPrefijo, true);
        $salida .= self::imprimirTexto($nodo->der, $nuevoPrefijo, false);

        return $salida;
    }
}

$arbolTexto = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pre = !empty($_POST['preorden']) ? array_map('trim', explode(',', $_POST['preorden'])) : [];
    $in = !empty($_POST['inorden']) ? array_map('trim', explode(',', $_POST['inorden'])) : [];
    $post = !empty($_POST['postorden']) ? array_map('trim', explode(',', $_POST['postorden'])) : [];

    $raiz = null;

    // Prioridad por defecto: si están los 3 o Preorden + Inorden
    if (!empty($pre) && !empty($in)) {
        $raiz = ConstructorArbol::desdePreIn($pre, $in);
    } elseif (!empty($in) && !empty($post)) {
        $raiz = ConstructorArbol::desdeInPost($in, $post);
    } elseif (!empty($pre) && !empty($post)) {
        $error = "Para construir el árbol unívocamente se requiere el recorrido Inorden.";
    } else {
        $error = "Debe ingresar al menos dos recorridos válidos.";
    }

    if ($raiz) {
        $arbolTexto = ConstructorArbol::imprimirTexto($raiz);
    } elseif (!$error) {
        $error = "No se pudo construir el árbol. Verifique los datos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Punto 6 - Árbol Binario</title>
    <link rel="stylesheet" href="../Vista.css">
</head>

<body>
    <h2>6. Construir Árbol Binario</h2>
    <form action="" method="POST">
        <p>Ingrese al menos dos recorridos (elementos separados por comas):</p>

        <div>
            <label for="preorden">Preorden:</label>
            <input type="text" id="preorden" name="preorden" value="<?= htmlspecialchars($_POST['preorden'] ?? '') ?>" placeholder="Ej: A, B, D, E, C">
        </div>
        <div>
            <label for="inorden">Inorden:</label>
            <input type="text" id="inorden" name="inorden" value="<?= htmlspecialchars($_POST['inorden'] ?? '') ?>" placeholder="Ej: D, B, E, A, C">
        </div>
        <div>
            <label for="postorden">Postorden:</label>
            <input type="text" id="postorden" name="postorden" value="<?= htmlspecialchars($_POST['postorden'] ?? '') ?>" placeholder="Ej: D, E, B, C, A">
        </div>

        <button type="submit">Construir Árbol</button>
    </form>

    <?php if ($arbolTexto): ?>
        <h3>Estructura del Árbol Generado:</h3>
        <pre class="arbol-view">
            <?= htmlspecialchars($arbolTexto) ?>
        </pre>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <a href="../index.php">Regresar</a>
</body>

</html>