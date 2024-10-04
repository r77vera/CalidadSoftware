<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/Controlador_Categoria.php'; // Cambia esta ruta si es necesario

final class ControladorCategoriaTest extends TestCase
{
    private Controlador_Categoria $controladorCategoria;
    private $modeloCategoria;

    protected function setUp(): void
    {
        // Crear un mock para la clase Modelo_Categoria
        $this->modeloCategoria = $this->createMock(Modelo_Categoria::class);
        $this->controladorCategoria = new Controlador_Categoria($this->modeloCategoria);
    }

    public function testListarCategoria(): void
    {
        $this->modeloCategoria->method('listarCategoria')->willReturn(['data' => [['id' => 1, 'nombre' => 'Categoria 1']]]);

        ob_start();
        $this->controladorCategoria->listarCategoria();
        $output = ob_get_clean();

        $this->assertJsonStringEqualsJsonString(
            json_encode(['data' => [['id' => 1, 'nombre' => 'Categoria 1']]]),
            $output
        );
    }

    public function testRegistrarCategoria(): void
    {
        $_POST['categoria'] = 'Nueva Categoria';

        $this->modeloCategoria->method('registrarCategoria')->willReturn('true');

        ob_start();
        $this->controladorCategoria->registrarCategoria();
        $output = ob_get_clean();

        $this->assertSame('true', $output);
    }

    public function testModificarCategoria(): void
    {
        $_POST['id'] = 1;
        $_POST['categoriaactual'] = 'Categoria Actual';
        $_POST['categorianuevo'] = 'Categoria Nueva';
        $_POST['estatus'] = 'Activo';

        $this->modeloCategoria->method('modificarCategoria')->willReturn('true');

        ob_start();
        $this->controladorCategoria->modificarCategoria();
        $output = ob_get_clean();

        $this->assertSame('true', $output);
    }
}
