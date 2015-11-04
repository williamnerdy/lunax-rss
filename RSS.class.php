<?php
/**
 * Classe para facilitar o desenvolvimento de RSS
 */
class RSS
{
	# Address of file
	private $fileRSS;

	# Data of DOM
	private $dom;

	# Get one tag by name
	private function getTagByName(string $name)
	{
		$nodeList = $this->dom->getElementsByTagName($name);
		return $nodeList->item(0);
	}

	# Save data on file
	private function save()
	{
		$this->dom->save($this->fileRSS);
	}

	private function loadFile()
	{
		$rss = file_get_contents($this->fileRSS);
		$this->dom = new DOMDocument;
		$this->dom->loadXML($rss);
	}

	private function createFile(array $data)
	{
		# Create the XML
		$this->dom = new DOMDocument($data['version_xml'], $data['encoding_xml']);

		# Create the RSS
		$rss = $this->dom->createElement('rss');
		$rss = $this->dom->appendChild($rss);

		# Add the RSS version
		$versionRSS = $this->dom->createAttribute('version');
		$versionRSS->value = $data['version_rss'];
		$rss->appendChild($versionRSS);

		# Create the channel
		$channel = $this->dom->createElement('channel');
		$channel = $rss->appendChild($channel);

		$required = [
			'title',
			'description',
			'link'
		];

		foreach ($required as $name) {
			if (isset($data[$name]) && !empty($data[$name])) {
				$dataInsert = $this->dom->createElement($name, $data[$name]);
				$channel->appendChild($dataInsert);
			} else {
				throw new Exception("Fatal error: $name not found!", 1);
			}
		}

		$optional = [
			'language',
			'copyright',
			'managingEditor',
			'webMaster',
			'pubDate',
			'lastBuildDate',
			'category',
			'generator',
			'docs',
			'ttl',
			'skipHours',
			'skipDays'
		];

		foreach ($optional as $name) {
			if (isset($data[$name]) && !empty($data[$name])) {
				$dataInsert = $this->dom->createElement($name, $data[$name]);
				$channel->appendChild($dataInsert);
			}
		}

		if (isset($data['image']) && gettype($data['image']) === 'array') {
			$image = $this->dom->createElement('image');

			$imageContent = [
				'url',
				'title',
				'link'
			];

			foreach ($imageContent as $name) {
				if (isset($data['image'][$name]) && !empty($data['image'][$name])) {
					$imageData = $this->dom->createElement($name, $data['image'][$name]);
					$image->appendChild($imageData);
				}
			}

			$channel->appendChild($image);
		}

		if (isset($data['textInput']) && gettype($data['textInput']) === 'array') {
			$textInput = $this->dom->createElement('textInput');

			$textInputContent = [
				'title',
				'description',
				'name',
				'link'
			];

			foreach ($textInputContent as $name) {
				if (isset($data['textInput'][$name]) && !empty($data['textInput'][$name])) {
					$textInputData = $this->dom->createAttribute($name);
					$textInputData->value = $data['textInput'][$name];
					$textInput->appendChild($textInputData);
				}
			}

			$channel->appendChild($textInput);
		}

		if (isset($data['cloud']) && gettype($data['cloud']) === 'array') {
			$cloud = $this->dom->createElement('cloud');

			$cloudContent = [
				'domain',
				'port',
				'path',
				'registerProcedure',
				'protocol'
			];

			foreach ($cloudContent as $name) {
				if (isset($data['cloud'][$name]) && !empty($data['cloud'][$name])) {
					$cloudData = $this->dom->createAttribute($name);
					$cloudData->value = $data['cloud'][$name];
					$cloud->appendChild($cloudData);
				}
			}

			$channel->appendChild($cloud);
		}

		# Save the data
		$this->save();
	}

	public function setEncodingXML(string $encoding)
	{
		if (array_search($encoding, mb_list_encodings())) {
			$this->dom->encoding = $encoding;
			$this->save();
		} else {
			throw new Exception("Invalid Document Encoding", 1);
		}
	}

	public function setVersionXML(string $version)
	{
		$this->dom->xmlVersion = $version;
	}

	public function setTitle(string $title)
	{
		$title = $this->getTagByName("title");
		$title->nodeValue = $title;
	}

	public function setDescription(string $description)
	{
		$description = $this->getTagByName("description");
		$description->nodeValue = $description;
	}

	public function setCategory(string $category)
	{
		$category = $this->getTagByName("category");
		$category->nodeValue = $category;
	}

	public function setLink(string $link)
	{
		$link = $this->getTagByName("link");
		$link->nodeValue = $link;
	}

	public function setLanguage(string $language)
	{
		$language = $this->getTagByName("language");
		$language->nodeValue = $language;
	}

	public function setCopyright(string $copyright)
	{
		$copyright = $this->getTagByName("copyright");
		$copyright->nodeValue = $copyright;
	}

	public function setImage(array $data)
	{
		$image = $this->dom->createElement('image');

		$imageContent = [
			'url',
			'title',
			'link'
		];

		foreach ($imageContent as $name) {
			if (isset($dataImage[$name]) && !empty($dataImage[$name])) {
				$imageData = $this->dom->createElement($name, $dataImage[$name]);
				$image->appendChild($imageData);
			}
		}

		$channel = $this->getTagByName('channel');
		$currentImage = $this->getTagByName('image');

		# Remove the current image
		if ($currentImage) {
			$channel->removeChild($currentImage);
			$this->save();
		}

		$channel->appendChild($image);
		$this->save();
	}

	private function getItemByPosition(int $position)
	{
		$nodeList = $this->dom->getElementsByTagName('item');
		return $nodeList->item($position - 1);
	}

	public function addItem(array $data)
	{
		# Make a new item
		$item = $this->dom->createElement('item');

		# Options required
		$required = [
			'title',
			'description',
			'link'
		];

		# Insert the required content
		foreach ($required as $name) {
			if (isset($data[$name]) && !empty($data[$name])) {
				$dataItem = $this->dom->createElement($name, $data[$name]);
				$item->appendChild($dataItem);
			} else {
				throw new Exception("Error $name not found!", 1);
			}
		}

		# Options not required
		$optional = [
			'author',
			'comments',
			'guid',
			'pubDate'
		];

		# Insert the optional content
		foreach ($optional as $name) {
			if (isset($data[$name]) && !empty($data[$name])) {
				$dataItem = $this->dom->createElement($name, $data[$name]);
				$item->appendChild($dataItem);
			}
		}

		# Insert the categories
		if (isset($data['categories']) && gettype($data['categories']) === 'array') {
			foreach ($data['categories'] as $category) {
				$categoryItem = $this->dom->createElement('category',  $category['name']);

				if (isset($category['domain'])) {
					$domain = $this->dom->createAttribute('domain');
					$domain->value = $category['domain'];
					$categoryItem->appendChild($domain);
				}

				$item->appendChild($categoryItem);
			}
		}

		if (isset($data['enclosure']) && gettype($data['enclosure']) == 'array') {
			$enclosure = $this->dom->createElement('enclosure');

			$enclosureContent = [
				'url',
				'length',
				'type'
			];

			foreach ($enclosureContent as $name) {
				if (isset($data['enclosure'][$name]) && !empty($data['enclosure'][$name])) {
					$enclosureData = $this->dom->createAttribute($name);
					$enclosureData->value = $data['enclosure'][$name];
					$enclosure->appendChild($enclosureData);
				}
			}

			$item->appendChild($enclosure);
		}

		if (isset($data['source']) && gettype($data['source']) === 'array') {
			$source = $this->dom->createElement('source', $data['source']['name']);

			if (isset($data['source']['url']) && !empty($data['source']['url'])) {
				$sourceURL = $this->dom->createAttribute('url');
				$sourceURL->value = $data['source']['url'];
				$source->appendChild($sourceURL);
			}

			$item->appendChild($source);
		}

		# Add item to channel
		$channel = $this->getTagByName('channel');
		$channel->appendChild($item);

		# Save changes
		$this->save();
	}

	public function changeItem(int $position, array $data)
	{
		$item = $this->getItemByPosition($position);

		$dataChange = [
			'title',
			'description',
			'link',
			'author',
			'comments',
			'guid',
			'pubDate'
		];

		foreach ($dataChange as $name) {
			if (isset($data[$name]) && !empty($data[$name])) {
				$modify = $item->getElementsByTagName($name)->item(0);
				$modify->nodeValue = $data[$name];
			}
		}

		if (isset($data['enclosure']) && gettype($data['enclosure']) == 'array') {

			$enclosure = $this->dom->createElement('enclosure');

			$enclosureContent = [
				'url',
				'length',
				'type'
			];

			foreach ($enclosureContent as $name) {
				if (isset($data['enclosure'][$name]) && !empty($data['enclosure'][$name])) {
					$enclosureData = $this->dom->createAttribute($name);
					$enclosureData->value = $data['enclosure'][$name];
					$enclosure->appendChild($enclosureData);
				}
			}

			$currentEnclosure = $item->getElementsByTagName('enclosure')->item(0);

			if ($currentEnclosure) {
				$item->removeChild($currentEnclosure);
			}

			$item->appendChild($enclosure);
		}

		if (isset($data['source']) && gettype($data['source']) == 'array') {

			$source = $this->dom->createElement('source', $data['source']['name']);

			if (isset($data['source']['url']) && !empty($data['source']['url'])) {
				$sourceURL = $this->dom->createAttribute('url');
				$sourceURL->value = $data['source']['url'];
				$source->appendChild($sourceURL);
			}

			$currentSource = $item->getElementsByTagName('source')->item(0);

			if ($currentSource) {
				$item->removeChild($currentSource);
			}

			$item->appendChild($source);
		}

		if (isset($data['categories']) && gettype($data['categories']) === 'array') {

			# Remove others categories
			$categories = $item->getElementsByTagName('category');

			while ($category = $categories->item(0)) {
				$item->removeChild($category);
			}

			# Insert the categories
			foreach ($data['categories'] as $category) {
				$categoryItem = $this->dom->createElement('category',  $category['name']);

				if (isset($category['domain'])) {
					$domain = $this->dom->createAttribute('domain');
					$domain->value = $category['domain'];
					$categoryItem->appendChild($domain);
				}

				$item->appendChild($categoryItem);
			}
		}

		# Save the data
		$this->save();
	}

	public function removeItem(int $position)
	{
		$channel = $this->getTagByName('channel');
		$item = $this->getItemByPosition($position);
		if ($item) {
			$channel->removeChild($item);
			$this->save();
		} else {
			throw new Exception("Item \"$position\" not found!", 1);
		}
	}

	function __construct($fileRSS, $dataRSS = [])
	{
		$this->fileRSS = $fileRSS;

		if (!file_exists($fileRSS)) {
			$this->createFile($dataRSS);
		} else {
			$this->loadFile();
		}
	}
}
