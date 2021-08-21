<?php

class BaselibCollection implements Iterator, ArrayAccess
{
    //The number of elements from the Collection
    public $length=0;
    protected $array_elements = array();


    /** Adding an object into a collection
     * @param object arg1 The value to add. it can be any type og object, string, int etc.
    */
    public function add($arg1, $arg2=false)
    {
        if (!$arg2) {
            $this->array_elements[] = $arg1;
        } else {
            if (!array_key_exists($arg1, $this->array_elements)) {
                $this->array_elements[$arg1] = $arg2;
            }
        }
        $this->count();
        return $this;
    }

    //Setting a value for a specified key of the array_elements
    public function set($key, $item)
    {
        if (isset($key)) {
            $this->array_elements[$key] = $item;
        } else {
            $this->array_elements[] = $item ;
        }
        $this->count();
        return $this->get($key);
    }
      
    //Sorting the array by values
    public function asort($flags=null)
    {
        asort($this->array_elements, $flags);
        return $this;
    }
   
    //Sorting the array by keys
    public function ksort($flags=null)
    {
        ksort($this->array_elements, $flags);
        return $this;
    }

    //Sorting the array naturally
    public function sort($flags=null)
    {
        sort($this->array_elements, $flags);
        return $this ;
    }
    
    //Getting the length of the array
    public function count()
    {
        $this->lenght = count($this->array_elements);
        return $this->lenght;
    }
    
    //Removing a specified kye
    public function remove($key)
    {
        if (array_key_exists($key, $this->array_elements)) {
            unset($this->array_elements[$key]);
            $this->count();
            return $this;
        }
    }
   
    //Moving the cursor a step forward
    public function next()
    {
        return next($this->array_elements) ;
    }

    //Cheking if the next element is valid
    public function hasNext()
    {
        $this->next() ;
        $v = $this->valid() ;
        $this->back() ;
        return $v ;
    }
       
    //Moves the cursor a step back
    public function back()
    {
        return prev($this->array_elements);
    }

    //Moves the cursor at start
    public function rewind()
    {
        return reset($this->array_elements);
    }

    //Moves the cursor at the end
    public function forward()
    {
        return end($this->array_elements);
    }

    //Getting the key from the pointed cursor
    public function current()
    {
        return current($this->array_elements);
    }

    //Getting the current cursor of the key
    public function currentKey()
    {
        return key($this->array_elements) ;
    }

    //Getting the cursor of the key
    public function key()
    {
        return $this->currentKey();
    }

    //Checking if the cursor is at a valid item
    public function valid()
    {
        if (!is_null($this->key())) {
            return true;
        } else {
            return false ;
        }
    }
    
    //Returning object for given posistion
    public function get($key)
    {
        return $this->array_elements[$key];
    }

    public function getAll()
    {
        return $this->array_elements;
    }

    //Checking if an offset exists using Array Access interface
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    //Getting an element using Array Access interface
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    //Setting an element using Array Access interface
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    //Removing element using Array Access interface
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    //Checking if the collection is empty or not
    public function isEmpty()
    {
        if ($this->count() < 1) {
            return true ;
        } else {
            return false;
        }
    }

    //Checking if a given object exists in collection
    public function contains($obj)
    {
        foreach ($this->array_elements as $element) {
            if ($element === $obj) {
                $this->rewind();
                return true ;
            }
        }
        $this->rewind();
        return false ;
    }

    //Returning the first index of given object
    public function indexOf($obj)
    {
        foreach ($this->array_elements as $k=>$element) {
            if ($element === $obj) {
                $this->rewind();
                return $k ;
            }
        }
        $this->rewind();
        return null ;
    }

    //Cutting the array to given size
    public function trimToSize($size)
    {
        $t = array_chunk($this->array_elements, $size, true);
        $this->array_elements = $t[0];
        $this->count();
        return $this;
    }
}
