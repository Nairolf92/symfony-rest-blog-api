<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Author", inversedBy="articles")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Commentary", mappedBy="article")
     */
    private $commentary;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", mappedBy="article")
     */
    private $categories;

    public function __construct()
    {
        $this->author = new ArrayCollection();
        $this->commentary = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->author->contains($author)) {
            $this->author->removeElement($author);
        }

        return $this;
    }

    /**
     * @return Collection|Commentary[]
     */
    public function getCommentary(): Collection
    {
        return $this->commentary;
    }

    public function addCommentary(Commentary $commentary): self
    {
        if (!$this->commentary->contains($commentary)) {
            $this->commentary[] = $commentary;
            $commentary->setArticle($this);
        }

        return $this;
    }

    public function removeCommentary(Commentary $commentary): self
    {
        if ($this->commentary->contains($commentary)) {
            $this->commentary->removeElement($commentary);
            // set the owning side to null (unless already changed)
            if ($commentary->getArticle() === $this) {
                $commentary->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addArticle($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeArticle($this);
        }

        return $this;
    }
}
