<?hh

function add_one(int $x): int {
	return $x + 1;
}

var_dump( add_one(3) );
var_dump( add_one('3') );


class Box<T> {
	protected T $data;

	public function __construct(T $data) {
		$this->data = $data;
	}

	public function getData(): T {
		return $this->data;
	}
}


$Box = new Box<string>('Some string');
var_dump($Box->getData());


#function build_paragraph(string $text, string $style): :div {
#  return
#    <div style={$style}>
#      <p>{$text}</p>
#    </div>;
#}

#$div = build_paragraph('Hello!', 'color: red');