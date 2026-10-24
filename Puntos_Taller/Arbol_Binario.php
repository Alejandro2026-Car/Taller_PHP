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

    // Genera la estructura HTML visual para el árbol
    public static function imprimirHTML(?NodoArbol $nodo): string
    {
        if ($nodo === null) return '';

        $html = '<li>';
        $html .= '<div class="nodo-valor">' . htmlspecialchars($nodo->valor) . '</div>';

        if ($nodo->izq !== null || $nodo->der !== null) {
            $html .= '<ul>';
            $html .= self::imprimirHTMLVisual($nodo->izq, 'izq');
            $html .= self::imprimirHTMLVisual($nodo->der, 'der');
            $html .= '</ul>';
        }

        $html .= '</li>';
        return $html;
    }

    private static function imprimirHTMLVisual(?NodoArbol $nodo, string $lado): string
    {
        if ($nodo === null) {
            // Nodo nulo auxiliar para mantener la simetría gráfica
            return '<li class="nodo-vacio"><div class="nodo-valor vacio">∅</div></li>';
        }
        return self::imprimirHTML($nodo);
    }
}

$arbolHTML = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pre = !empty($_POST['preorden']) ? array_filter(array_map('trim', explode(',', $_POST['preorden']))) : [];
    $in = !empty($_POST['inorden']) ? array_filter(array_map('trim', explode(',', $_POST['inorden']))) : [];
    $post = !empty($_POST['postorden']) ? array_filter(array_map('trim', explode(',', $_POST['postorden']))) : [];

    $raiz = null;

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
        $arbolHTML = ConstructorArbol::imprimirHTML($raiz);
    } elseif (!$error) {
        $error = "No se pudo construir el árbol. Verifique los datos ingresados.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Punto 6 - Árbol Binario</title>
    <link rel="stylesheet" href="../Vista/Punto6.css">
</head>

<body>
    <div class="capa-desenfoque">
        <h1>Arbol Binario</h1>
    </div>
    <div class="container">
        <h2>6. Construir Árbol Binario</h2>
        <form action="" method="POST">
            <p>Ingrese al menos dos recorridos (elementos separados por comas):</p>

            <div class="input-row">
                <label for="preorden">Preorden:</label>
                <input type="text" id="preorden" name="preorden" value="<?= htmlspecialchars($_POST['preorden'] ?? '') ?>" placeholder="Ej: A, B, D, E, C">
            </div>
            <div class="input-row">
                <label for="inorden">Inorden:</label>
                <input type="text" id="inorden" name="inorden" value="<?= htmlspecialchars($_POST['inorden'] ?? '') ?>" placeholder="Ej: D, B, E, A, C">
            </div>
            <div class="input-row">
                <label for="postorden">Postorden:</label>
                <input type="text" id="postorden" name="postorden" value="<?= htmlspecialchars($_POST['postorden'] ?? '') ?>" placeholder="Ej: D, E, B, C, A">
            </div>
            <div class="container">
                <button type="submit">Construir Árbol</button>
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
            <?php if ($error): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($arbolHTML): ?>
                <h3>Resultado Visual:</h3>
                <div class="arbol-visual">
                    <ul>
                        <?= $arbolHTML ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>