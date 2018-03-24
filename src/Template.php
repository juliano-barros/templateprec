<?php

namespace Template;
/**
 * Created by PhpStorm.
 * User: julia
 * Date: 2018-03-23
 * Time: 7:14 AM
 */

class Template
{
    /**
     * @var bool|string
     */
    private $content = '';
    /**
     * @var array
     */
    private $variables = [];
    /**
     * @var array
     */
    private $arrays = [];

    /**
     * @var string
     */
    private $basePath = '';

    /**
     * @var string
     */
    private $extensionFile = 'tmpl';

    /**
     * @param string $extension
     */
    public function setExtensionFile(string $extension): void
    {
        $this->extensionFile = $extension;
    }

    /**
     * @param string $basePath
     */
    public function setBasePath(string $basePath): void
    {
        $this->basePath = $basePath;
    }


    /**
     * Template constructor.
     * @param array $variables
     */
    public function __construct(array $variables = [] )
    {

        $keys = array_keys($variables);
        foreach ($keys as $key){
            $this->addVariable($key, $variables[$key]);
        }
    }

    /**
     * @param string $filename
     */
    public function render( string $filename ): void{

        $this->loadTemplate($filename);
        // For each first
        $this->compileForeachs();
        // Variables, arrays of foreachs
        $this->compileVariables();
        // Unless condition
        $this->compileUnless();
        // Cheking @last inside of foreachs
        $this->compileLast();
        // Eval of content
        eval( '?>' . $this->getContent() );
    }

    /**
     *
     */
    private function compileForeachs(): void{

        // Replacing php foreach, creating a variable total itens of array
        $this->setContent( preg_replace('/{{#each (.*?)}}/', '<?php foreach( {{<$1>}} as $index => $item ){ $__total__ = count({{<$1>}}) - 1; ?>', $this->getContent() ) );
        $this->setContent( preg_replace('/{{\/each}}/', '<?php };?>', $this->getContent() ) );

    }

    /**
     * @param string $filename
     * @return string
     */
    private function getFileName(string $filename): string{
        return $this->getBasePath() . $filename . '.' . $this->getExtensionFile();
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @return string
     */
    public function getExtensionFile(): string
    {
        return $this->extensionFile;
    }

    /**
     * @return bool|string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param bool|string $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @param array $variables
     */
    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }

    /**
     * @return array
     */
    public function getArrays(): array
    {
        return $this->arrays;
    }

    /**
     * @param array $arrays
     */
    public function setArrays(array $arrays): void
    {
        $this->arrays = $arrays;
    }

    /**
     * @param string $filename
     */
    private function loadTemplate( string $filename){
        if ( $filename !== "" ){
            if ( file_exists($this->getFileName($filename))){
                $this->setContent( file_get_contents($this->getFileName($filename)) );
            }else{
                echo "File not found: " . $this->getFileName($filename);
            }
        }
    }

    /**
     * Adding variables
     * @param string $key
     * @param $value
     */
    public function addVariable( string $key, $value): void{
        // It is allowed variables and arrays only
        if (is_array($value)){
            $this->arrays[$key] = $value;
        }else{
            $this->variables[$key] = $value;
        }
    }

    /**
     * Compile variables and arrays
     *
     * Arrays must be used inside of foreach
     */
    private function compileVariables(): void
    {
        // Compiling variables
        foreach ( $this->variables as $key => $value ){
            $this->setContent(preg_replace('/{{' . $key . '}}/', $value, $this->getContent()));
        }
        // Compiling arrays, must be used inside of foreach
        foreach ( $this->arrays as $keyaux => $values ){
            $this->setContent( preg_replace('/{{<' . $keyaux  .'>}}/', '$this->arrays["' . $keyaux .'"]', $this->getContent() ) );
            $this->setContent( preg_replace('/{{' . $keyaux  .'\[(.*)}}/', '<?= $this->arrays["' . $keyaux .'"][$1?>', $this->getContent() ) );
            foreach ($values as $valueArray) {
                $keys = array_keys($valueArray);
                break;
            }
            foreach ( $keys as $key ){
                $this->setContent( preg_replace('/{{' . $key . '}}/', '<?= $item["' . $key . '"]?>', $this->getContent() ) );
            }
        }
    }

    /**
     * Unless statements
     */
    private function compileUnless(): void{
        // Checking unless parameters
        $this->setContent( preg_replace('/{{#unless (.*?)}}/', '<?php if (!($1)) :?>', $this->getContent() ) );
        $this->setContent( preg_replace('/{{else}}/', '<?php else: ?>', $this->getContent() ) );
        $this->setContent( preg_replace('/{{\/unless}}/', '<?php endif;?>', $this->getContent() ) );
    }

    /**
     * @last functions inside of foreachs
     */
    private function compileLast(): void{
        // Function @last of foreach
        $this->setContent( preg_replace('/@last/', '$index === $__total__', $this->getContent() ) );
    }


}

