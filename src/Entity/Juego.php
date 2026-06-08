<?php

namespace App\Entity;

use App\Repository\JuegoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JuegoRepository::class)]
class Juego
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titulo = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?float $precio = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $fecha = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rutaImagen = null;

    #[ORM\ManyToOne(inversedBy: 'juegos')]
    private ?CategoriaJuego $categoria = null;

    #[ORM\ManyToOne(inversedBy: 'juegos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $autor = null;

    /**
     * @var Collection<int, Usuario>
     */
    #[ORM\ManyToMany(targetEntity: Usuario::class, mappedBy: 'juegosComprados')]
    private Collection $usuariosVendido;

    /**
     * @var Collection<int, Comentario>
     */
    #[ORM\OneToMany(targetEntity: Comentario::class, mappedBy: 'juego')]
    private Collection $comentarios;

    public function __construct()
    {
        $this->usuariosVendido = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->precio;
    }

    public function setPrecio(float $precio): static
    {
        $this->precio = $precio;

        return $this;
    }

    public function getFecha(): ?\DateTime
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getRutaImagen(): ?string
    {
        return $this->rutaImagen;
    }

    public function setRutaImagen(string $rutaImagen): static
    {
        $this->rutaImagen = $rutaImagen;

        return $this;
    }

    public function getCategoria(): ?CategoriaJuego
    {
        return $this->categoria;
    }

    public function setCategoria(?CategoriaJuego $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getAutor(): ?Usuario
    {
        return $this->autor;
    }

    public function setAutor(?Usuario $autor): static
    {
        $this->autor = $autor;

        return $this;
    }

    /**
     * @return Collection<int, Usuario>
     */
    public function getUsuariosVendido(): Collection
    {
        return $this->usuariosVendido;
    }

    public function addUsuariosVendido(Usuario $usuariosVendido): static
    {
        if (!$this->usuariosVendido->contains($usuariosVendido)) {
            $this->usuariosVendido->add($usuariosVendido);
            $usuariosVendido->addJuegosComprado($this);
        }

        return $this;
    }

    public function removeUsuariosVendido(Usuario $usuariosVendido): static
    {
        if ($this->usuariosVendido->removeElement($usuariosVendido)) {
            $usuariosVendido->removeJuegosComprado($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Comentario>
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(Comentario $comentario): static
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios->add($comentario);
            $comentario->setJuego($this);
        }

        return $this;
    }

    public function removeComentario(Comentario $comentario): static
    {
        if ($this->comentarios->removeElement($comentario)) {
            // set the owning side to null (unless already changed)
            if ($comentario->getJuego() === $this) {
                $comentario->setJuego(null);
            }
        }

        return $this;
    }
}
