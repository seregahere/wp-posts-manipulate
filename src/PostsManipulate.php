<?php

namespace Seregahere\WPPostsManipulate;

use Seregahere\WPPostsManipulate\Base\Singleton;
use WP_Query;
use WP_Post;

class PostsManipulate extends Singleton
{
    const POST_PERMALINK_KEY = 'custom_permalink';

    private $post = null;

    public function iteratePosts($args = array(), callable $callback)
    {
        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $this->post = $query->post;
                $callback($this->post);
            }
            wp_reset_postdata();
        }
    }

    public function getCustomPermalink($post = null)
    {
        $post = $this->getPostObject($post);
        $res = $this->getCustomField(self::POST_PERMALINK_KEY, $post);
        if (!$res) {
            $res = $post->post_name;
        }

        return $res;
    }

    public function setCustomPermalink($newPermalink, $post = null)
    {
        $post = $this->getPostObject($post);
        if (is_object($post)) {
            if (!add_post_meta($post->ID, self::POST_PERMALINK_KEY, $newPermalink, true)) {
                update_post_meta($post->ID, self::POST_PERMALINK_KEY, $newPermalink);
            }
        }

        return $this;
    }

    public function updatePost(WP_Post $post)
    {
        return wp_update_post($post);
    }

    public function getCustomField($fieldName, $post = null)
    {
        $post = $this->getPostObject($post);

        return $this->getMetaData($fieldName, (is_object($post) ? $post->ID : 0));
    }

    public function getPostById($postID)
    {
        return get_post($postID);
    }

    private function getMetaData($fieldName, $postID)
    {
        return get_post_meta($postID, $fieldName, true);
    }

    private function getPostObject($post = null)
    {
        if (!$post) {
            $res = $this->post ? $this->post : null;
        } else {
            $res = is_scalar($post) ? $this->getPostById((int) $post) : $post;
        }

        return $res;
    }
}

