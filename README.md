Lunax RSS
--
Classe em php para facilitar o desenvolvimento de RSS.

**Auhor:** will.cunha.nd@gmail.com

---

Cria um novo RSS, seta na inicialização o nome do arquivo, caso esse arquivo não esteja vazio os dados permanecerão

````php
$rss = new RSS("./file.rss");
````



Altera o encoding do XML
````php
$rss->setEncodingXML("UTF-8");
````



Altera a versão do XML
````php
$rss->setVersionXML("1.0");
````



Altera o título do site
````php
$rss->setTitle("Title page...");
````



Altera a descrição do site
````php
$rss->setDescription("Lorem ipsum dolor sit amet...");
````



Altera ou adiciona a categoria do site
````php
$rss->setCategory("Category");
````



Altera o link de acesso a página
````php
$rss->setLink("http://example.com");
````



Altera ou adiciona a linguagem utilizada
no RSS, não é obrigatório
````php
$rss->setLanguage("pt-br");
````



Adiciona ou altera o copyright,
não é obrigatório
````php
$rss->setCopyright("Info....");
````



Adiciona uma imagem
````php
$rss->setImage([
  "image"  => "http://example.com/file.png",
  "url"    => "http://example.com",
  "title"  => "Lorem ipsum dolor sit amet"
]);
````



Adiciona um novo item ao RSS
````php
$rss->addItem([
  "title"       => "Item title",
  "description" => "Item description",
  "link"        => "http://example.com/news/1",
  "comments"    => "http://example.com/news/1#comments",
  "author"      => "author@example.com",

  # Opcional
  "categories"  => ["category 1", "category 2"],

  # Opcional
  "pub_date"    => "Mon, 27 Jan 2001"
]);
````



Remove o item na posição 10
````php
$rss->removeItem(10);
````



Change um item do RSS, o primeiro parâmetro é a posição do item, o segundo é o que será alterado

````php
$rss->changeItem(10, [
  "title"   => "New item title",
  "author"  => "author2@example.com"
]);
````
