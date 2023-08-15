<pre><?php print_r((new Parser())->json_book);?></pre>
<?php 
/**
 * 
 */
class Parser {
	
	function __construct() {
		$this->url = 'https://code.mu';
		$this->table_url = 'https://www.code.mu/ru/markup/book/prime/';
		$this->items_list = $this->getItemsList();
		$this->items_list = $this->getDetailItemsList();
		$this->json_book = $this->saveBook();
	}

	function saveBook () {
		$str = json_encode($this->items_list, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		file_put_contents('book.json', $str);
		return $str;
	}

	function getDetailItemsList () {
		$arr = [];
		foreach ($this->items_list as $key => $value) {
			$arr[$key] = ['url' => $value, 'content' => $this->getItemContent($value)];
		}
		return $arr;
	}

	function getItemContent ($url) {
		$str = file_get_contents($this->url.$url);
		$arr = explode('<main>', $str);
		$str = $arr[1];
		$arr = explode('</main>', $str);
		return $arr[0];
	}

	function getItemsList () {
		$str = file_get_contents($this->table_url);
		preg_match_all('#data-lesson="(?:.*)href="(.*)"#', $str, $arr);
		return $arr[1];
	}
}

?>
