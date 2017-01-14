<?php
namespace Banana\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PostsFixture
 *
 */
class PostsFixture extends TestFixture
{

    /**
     * Table name
     *
     * @var string
     */
    //public $table = 'bc_posts';

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'refscope' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'refid' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'parent_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => true, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'type' => ['type' => 'string', 'length' => 45, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'title' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'slug' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'subheading' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'use_teaser' => ['type' => 'boolean', 'length' => null, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null],
        'teaser_html' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'teaser_link_href' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'teaser_link_caption' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'teaser_image_file' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'teaser_template' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'body_html' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'image_file' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'image_link_href' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'image_link_target' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'image_desc' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'image_files' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'template' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'cssclass' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'cssid' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'meta_description' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'meta_title' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'meta_desc' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'meta_keywords' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'meta_robots' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'meta_lang' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'is_published' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '0', 'comment' => '', 'precision' => null],
        'publish_start_datetime' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'publish_end_datetime' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'pos' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => '0', 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'section' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'fixed' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'refscope' => 'Test',
            'refid' => 1,
            'parent_id' => '',
            'type' => '',
            'title' => 'Lorem ipsum dolor sit amet',
            'slug' => 'Lorem ipsum dolor sit amet',
            'subheading' => 'Lorem ipsum dolor sit amet',
            'use_teaser' => 1,
            'teaser_html' => '<p>Lorem ipsum <strong>dolor</strong> sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</p>',
            'teaser_link_href' => '',
            'teaser_link_caption' => 'Lorem ipsum dolor sit amet',
            'teaser_image_file' => '',
            'teaser_template' => '',
            'body_html' => '<p>Lorem ipsum dolor <strong>sit amet</strong>, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</p>',
            'image_file' => '',
            'image_link_href' => 'Lorem ipsum dolor sit amet',
            'image_link_target' => '',
            'image_desc' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'image_files' => '',
            'template' => '',
            'cssclass' => '',
            'cssid' => '',
            'meta_description' => '',
            'meta_title' => '',
            'meta_desc' => '',
            'meta_keywords' => '',
            'meta_robots' => '',
            'meta_lang' => '',
            'is_published' => 1,
            'publish_start_datetime' => null,
            'publish_end_datetime' => null,
            'pos' => 1,
            'section' => '',
            'modified' => '2016-04-27 17:15:43',
            'created' => '2016-04-27 17:15:43'
        ],
        [
            'id' => 2,
            'refscope' => 'Test',
            'refid' => 2,
            'parent_id' => '',
            'type' => '',
            'title' => 'Lorem ipsum dolor sit amet',
            'slug' => 'Lorem ipsum dolor sit amet',
            'subheading' => 'Lorem ipsum dolor sit amet',
            'use_teaser' => 1,
            'teaser_html' => '<p>Lorem ipsum <strong>dolor</strong> sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</p>',
            'teaser_link_href' => '',
            'teaser_link_caption' => 'Lorem ipsum dolor sit amet',
            'teaser_image_file' => '',
            'teaser_template' => '',
            'body_html' => '<p>Lorem ipsum dolor <strong>sit amet</strong>, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</p>',
            'image_file' => '',
            'image_link_href' => 'Lorem ipsum dolor sit amet',
            'image_link_target' => '',
            'image_desc' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'image_files' => '',
            'template' => '',
            'cssclass' => '',
            'cssid' => '',
            'meta_description' => '',
            'meta_title' => '',
            'meta_desc' => '',
            'meta_keywords' => '',
            'meta_robots' => '',
            'meta_lang' => '',
            'is_published' => 1,
            'publish_start_datetime' => null,
            'publish_end_datetime' => null,
            'pos' => 2,
            'section' => '',
            'modified' => '2016-04-27 17:15:43',
            'created' => '2016-04-27 17:15:43'
        ],
        [
            'id' => 3,
            'refscope' => 'Test',
            'refid' => 3,
            'parent_id' => '',
            'type' => '',
            'title' => 'Lorem ipsum dolor sit amet',
            'slug' => 'Lorem ipsum dolor sit amet',
            'subheading' => 'Lorem ipsum dolor sit amet',
            'use_teaser' => 1,
            'teaser_html' => '<p>Lorem ipsum <strong>dolor</strong> sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</p>',
            'teaser_link_href' => '',
            'teaser_link_caption' => 'Lorem ipsum dolor sit amet',
            'teaser_image_file' => '',
            'teaser_template' => '',
            'body_html' => '<p>Lorem ipsum dolor <strong>sit amet</strong>, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</p>',
            'image_file' => '',
            'image_link_href' => 'Lorem ipsum dolor sit amet',
            'image_link_target' => '',
            'image_desc' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'image_files' => '',
            'template' => '',
            'cssclass' => '',
            'cssid' => '',
            'meta_description' => '',
            'meta_title' => '',
            'meta_desc' => '',
            'meta_keywords' => '',
            'meta_robots' => '',
            'meta_lang' => '',
            'is_published' => 1,
            'publish_start_datetime' => null,
            'publish_end_datetime' => null,
            'pos' => 3,
            'section' => '',
            'modified' => '2016-04-27 17:15:43',
            'created' => '2016-04-27 17:15:43'
        ],
        [
            'id' => 4,
            'refscope' => 'Test',
            'refid' => 4,
            'parent_id' => '',
            'type' => '',
            'title' => 'Lorem ipsum dolor sit amet',
            'slug' => 'Lorem ipsum dolor sit amet',
            'subheading' => 'Lorem ipsum dolor sit amet',
            'use_teaser' => 1,
            'teaser_html' => '<p>Lorem ipsum <strong>dolor</strong> sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</p>',
            'teaser_link_href' => '',
            'teaser_link_caption' => 'Lorem ipsum dolor sit amet',
            'teaser_image_file' => '',
            'teaser_template' => '',
            'body_html' => '<p>Lorem ipsum dolor <strong>sit amet</strong>, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.</p>',
            'image_file' => '',
            'image_link_href' => 'Lorem ipsum dolor sit amet',
            'image_link_target' => '',
            'image_desc' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'image_files' => '',
            'template' => '',
            'cssclass' => '',
            'cssid' => '',
            'meta_description' => '',
            'meta_title' => '',
            'meta_desc' => '',
            'meta_keywords' => '',
            'meta_robots' => '',
            'meta_lang' => '',
            'is_published' => 1,
            'publish_start_datetime' => null,
            'publish_end_datetime' => null,
            'pos' => 4,
            'section' => '',
            'modified' => '2016-04-27 17:15:43',
            'created' => '2016-04-27 17:15:43'
        ],
    ];
}
