
## Built-in Model Behaviors

### AttributesBehavior

#### Usage

```php
class Post extends Table {

    public function initialize() {
        $this->loadBehavior('Cupcake.Attributes', [
            'attributesTableName' => 'attributes',
            'attributesPropertyName' => 'attrs',
            'attributes' => [
                'foo' => ['type' => 'string', 'required' => true, 'default' => null]
            ],
        ]);
    }
    
    /**
     * Dynamically add attributes to the attributes schema with Model method.
     */
    public function buildAttributes($attributes)
    {
        // dynamically add attributes
        return $attributes;
    }
    
    /**
     * Dynamically add attributes to the attributes schema with EventListener.
     */
    public function onBuildAttributes(\Cake\Event\EventInterface $event, \ArrayObject $attributes)
    {
        $attributes = $event->getData('attributes');
        $attributes['my_new_attr'] = [...];
        $event->setData($attributes);
    }
    
    public function implementedEvents()
    {
        return ['Model.buildAttributes' => 'onBuildAttributes'];
    }


}
```

#### Finders

| Method    | Description    |
| --- | --- |
| withAttributes | Find all records with all their attributes |
| byAttribute | Find all records with given key-value attribute pair(s) |
| havingAttribute | Find all records that have the given attribute key |

#### Methods
| Method    | Description    |
| --- | --- |
| createAttribute($entity, $name, $val) | Find or create an attribute for an entity | 
| saveAttribute($attr) | Insert or update an attribute | 
| isAttribute($name) | Check if a model's field is a registered attribute | 
| getAttributesTable() | Get the instance of the related AttributesTable | 
| getAttributesSchema() | Get the instance of the model's attributes schema | 

### CopyBehavior

### PublishBehavior

### SimpleTreeBehavior

### SlugBehavior

### StatusableBehavior

