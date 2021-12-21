<?php 
namespace XcaleGroup;

class FileReader
{
    protected $file;
    protected $resume;
    protected $linenumber;
    protected $line;
    protected $fileobj;
    public $destroy;

    /**
     * Constructor
     * @param file the path and filename of which you want to read data
     * @param bool Resume. if true it will read from the resume file and start there. Dewfaults to false
     * @param int Linenumber to start reading from. Defaults to 1
     */    
    function __construct($file, $resume = false, $linenumber = 1){
        $this->destroy = false;
        $this->file = $file;
        $this->resume = $resume;
        $this->linenumber = $linenumber;

        $this->fileobj = new SplFileObject($this->file);

        if($this->resume){
            $this->linenumber = $this->getCurrentlinenumber();
        }

        $this->fileobj->seek($this->linenumber-1);
    }

    /**
     * Destructor. Writes the current linenumber to the resume file
     */
    function __destruct()
    {
        $this->setCurrentlinenumber();
    }

    /**
     * Read the file from the linenumber set in the constructor or via Resume option
     * When each line is read the content os return to the callback
     * @param callback Your callback function to handle line content
     */
    public function read(callable $callback){
        if ($this->linenumber == 0) { // eof
            exit;
        }

        while ( !$this->fileobj->eof() || $this->destroy){
            $this->linenumber++;
            $this->line = $this->fileobj->current();
            $callback($this->line); // call the callback to handle the data
            $this->fileobj->seek($this->linenumber-1);
        }

        if($this->destroy)
            $this->setCurrentlinenumber();
    }

    /** 
     * Gets the line number from the resume file
     * Return 0 if eof is reached
    */
    protected function getCurrentlinenumber(){
        $filename = $this->file .'.txt';
        if (!file_exists($filename)) {
            return 1;
        } // First line
        else {
            $content = file_get_contents($filename); // Read the content of the file
            if (empty($content)) {
                return 1;
            } elseif($content == 'eof') {
                return 0;
            } else {
                return intval($content);
            }
        }
    }

    /**
     * Writes the line number to the resume file
     */
    public function setCurrentlinenumber(){
        $filename = $this->file .'.txt';
        $content = "";
        if($this->fileobj->eof())
            $content = 'eof';
        else
            $content = $this->linenumber;

        file_put_contents($filename, $content); // Save the file
    }
    
    /** 
     * Returns the total number of lines in a file.
    */
    public function count()
    {
        // Create new file object to avoid moving the seek index.
        $file = new \SplFileObject($this->file, 'r');
        $file->seek(PHP_INT_MAX);
        $num = $file->key();
        unset($file);
        return $num;
    }

}
?>