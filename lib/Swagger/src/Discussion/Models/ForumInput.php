<?php
/**
 * ForumInput
 *
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   http://github.com/swagger-api/swagger-codegen
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * discussion
 *
 * No descripton provided (generated by Swagger Codegen https://github.com/swagger-api/swagger-codegen)
 *
 * OpenAPI spec version: 0.1.0-SNAPSHOT
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Discussion\Models;

use \ArrayAccess;

/**
 * ForumInput Class Doc Comment
 *
 * @category    Class */
/** 
 * @package     Swagger\Client
 * @author      http://github.com/swagger-api/swagger-codegen
 * @license     http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link        https://github.com/swagger-api/swagger-codegen
 */
class ForumInput implements ArrayAccess
{
    /**
      * The original name of the model.
      * @var string
      */
    protected static $swaggerModelName = 'ForumInput';

    /**
      * Array of property to type mappings. Used for (de)serialization
      * @var string[]
      */
    protected static $swaggerTypes = array(
        'site_id' => 'int',
        'parent_id' => 'int',
        'name' => 'string',
        'description' => 'string',
        'image_url' => 'string',
        'allows_threads' => 'bool'
    );

    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of attributes where the key is the local name, and the value is the original name
     * @var string[]
     */
    protected static $attributeMap = array(
        'site_id' => 'siteId',
        'parent_id' => 'parentId',
        'name' => 'name',
        'description' => 'description',
        'image_url' => 'imageUrl',
        'allows_threads' => 'allowsThreads'
    );

    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     * @var string[]
     */
    protected static $setters = array(
        'site_id' => 'setSiteId',
        'parent_id' => 'setParentId',
        'name' => 'setName',
        'description' => 'setDescription',
        'image_url' => 'setImageUrl',
        'allows_threads' => 'setAllowsThreads'
    );

    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     * @var string[]
     */
    protected static $getters = array(
        'site_id' => 'getSiteId',
        'parent_id' => 'getParentId',
        'name' => 'getName',
        'description' => 'getDescription',
        'image_url' => 'getImageUrl',
        'allows_threads' => 'getAllowsThreads'
    );

    public static function getters()
    {
        return self::$getters;
    }

    

    

    /**
     * Associative array for storing property values
     * @var mixed[]
     */
    protected $container = array();

    /**
     * Constructor
     * @param mixed[] $data Associated array of property value initalizing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['site_id'] = isset($data['site_id']) ? $data['site_id'] : null;
        $this->container['parent_id'] = isset($data['parent_id']) ? $data['parent_id'] : null;
        $this->container['name'] = isset($data['name']) ? $data['name'] : null;
        $this->container['description'] = isset($data['description']) ? $data['description'] : null;
        $this->container['image_url'] = isset($data['image_url']) ? $data['image_url'] : null;
        $this->container['allows_threads'] = isset($data['allows_threads']) ? $data['allows_threads'] : false;
    }

    /**
     * show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalid_properties = array();
        if ($this->container['site_id'] === null) {
            $invalid_properties[] = "'site_id' can't be null";
        }
        if ($this->container['parent_id'] === null) {
            $invalid_properties[] = "'parent_id' can't be null";
        }
        if ($this->container['name'] === null) {
            $invalid_properties[] = "'name' can't be null";
        }
        if ((strlen($this->container['name']) > 20)) {
            $invalid_properties[] = "invalid value for 'name', the character length must be smaller than or equal to 20.";
        }

        if ((strlen($this->container['name']) < 1)) {
            $invalid_properties[] = "invalid value for 'name', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['description']) && (strlen($this->container['description']) > 512)) {
            $invalid_properties[] = "invalid value for 'description', the character length must be smaller than or equal to 512.";
        }

        if (!is_null($this->container['description']) && (strlen($this->container['description']) < 0)) {
            $invalid_properties[] = "invalid value for 'description', the character length must be bigger than or equal to 0.";
        }

        return $invalid_properties;
    }

    /**
     * validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properteis are valid
     */
    public function valid()
    {
        if ($this->container['site_id'] === null) {
            return false;
        }
        if ($this->container['parent_id'] === null) {
            return false;
        }
        if ($this->container['name'] === null) {
            return false;
        }
        if (strlen($this->container['name']) > 20) {
            return false;
        }
        if (strlen($this->container['name']) < 1) {
            return false;
        }
        if (strlen($this->container['description']) > 512) {
            return false;
        }
        if (strlen($this->container['description']) < 0) {
            return false;
        }
        return true;
    }


    /**
     * Gets site_id
     * @return int
     */
    public function getSiteId()
    {
        return $this->container['site_id'];
    }

    /**
     * Sets site_id
     * @param int $site_id
     * @return $this
     */
    public function setSiteId($site_id)
    {
        $this->container['site_id'] = $site_id;

        return $this;
    }

    /**
     * Gets parent_id
     * @return int
     */
    public function getParentId()
    {
        return $this->container['parent_id'];
    }

    /**
     * Sets parent_id
     * @param int $parent_id
     * @return $this
     */
    public function setParentId($parent_id)
    {
        $this->container['parent_id'] = $parent_id;

        return $this;
    }

    /**
     * Gets name
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        if (strlen($name) > 20) {
            throw new \InvalidArgumentException('invalid length for $name when calling ForumInput., must be smaller than or equal to 20.');
        }
        if (strlen($name) < 1) {
            throw new \InvalidArgumentException('invalid length for $name when calling ForumInput., must be bigger than or equal to 1.');
        }
        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets description
     * @return string
     */
    public function getDescription()
    {
        return $this->container['description'];
    }

    /**
     * Sets description
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        if (strlen($description) > 512) {
            throw new \InvalidArgumentException('invalid length for $description when calling ForumInput., must be smaller than or equal to 512.');
        }
        if (strlen($description) < 0) {
            throw new \InvalidArgumentException('invalid length for $description when calling ForumInput., must be bigger than or equal to 0.');
        }
        $this->container['description'] = $description;

        return $this;
    }

    /**
     * Gets image_url
     * @return string
     */
    public function getImageUrl()
    {
        return $this->container['image_url'];
    }

    /**
     * Sets image_url
     * @param string $image_url
     * @return $this
     */
    public function setImageUrl($image_url)
    {
        $this->container['image_url'] = $image_url;

        return $this;
    }

    /**
     * Gets allows_threads
     * @return bool
     */
    public function getAllowsThreads()
    {
        return $this->container['allows_threads'];
    }

    /**
     * Sets allows_threads
     * @param bool $allows_threads
     * @return $this
     */
    public function setAllowsThreads($allows_threads)
    {
        $this->container['allows_threads'] = $allows_threads;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     * @param  integer $offset Offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     * @param  integer $offset Offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     * @param  integer $offset Offset
     * @param  mixed   $value  Value to be set
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     * @param  integer $offset Offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this), JSON_PRETTY_PRINT);
        }

        return json_encode(\Swagger\Client\ObjectSerializer::sanitizeForSerialization($this));
    }
}


