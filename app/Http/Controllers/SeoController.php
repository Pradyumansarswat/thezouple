<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Support\Facades\Schema;

class SeoController extends Controller
{
    public function robots()
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /masterAdmin\n";
        $content .= "Disallow: /storage\n";
        $content .= "Disallow: /vendor\n";
        $content .= "Disallow: /cart\n";
        $content .= "Disallow: /checkout\n";
        $content .= "Disallow: /wishlist\n\n";
        $content .= "Sitemap: " . url('sitemap.xml') . "\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }

    public function sitemap()
    {
        $urls = array(
            array('loc' => url('/'), 'priority' => '1.0'),
            array('loc' => url('about'), 'priority' => '0.7'),
            array('loc' => url('contact'), 'priority' => '0.7'),
            array('loc' => url('blog'), 'priority' => '0.6'),
        );

        if (Schema::hasTable('categorys')) {
            $categories = DB::table('categorys')->select('slug', 'updated_at')->whereNotNull('slug')->get();
            foreach ($categories as $category) {
                $urls[] = array('loc' => url('category/' . $category->slug), 'priority' => '0.8', 'lastmod' => $category->updated_at);
            }
        }

        if (Schema::hasTable('products')) {
            $products = DB::table('products')->select('slug', 'updated_at')->whereNotNull('slug')->get();
            foreach ($products as $product) {
                $urls[] = array('loc' => url('product/' . $product->slug), 'priority' => '0.9', 'lastmod' => $product->updated_at);
            }
        }

        if (Schema::hasTable('cms')) {
            $pages = DB::table('cms')->select('slug', 'updated_at')->whereNotNull('slug')->get();
            foreach ($pages as $page) {
                $urls[] = array('loc' => url('cms/' . $page->slug), 'priority' => '0.5', 'lastmod' => $page->updated_at);
            }
        }

        if (Schema::hasTable('blog')) {
            $blogs = DB::table('blog')->select('slug', 'updated_at')->whereNotNull('slug')->get();
            foreach ($blogs as $blog) {
                $urls[] = array('loc' => url('blogShow/' . $blog->slug), 'priority' => '0.6', 'lastmod' => $blog->updated_at);
            }
        }

        return response()->view('sitemap', array('urls' => $urls))->header('Content-Type', 'application/xml');
    }
}
