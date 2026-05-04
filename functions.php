<?php
// コンパイルファイルの読み込み
function tanren_enqueue_custom_assets() {
	$theme_data = wp_get_theme();
	$js_version = $theme_data->get( 'Version' );
	$css_version = $theme_data->get( 'Version' );

	if ( defined( 'WP_ENV' ) && WP_ENV === 'production' ) {
		// 本番環境ではmain.min.jsとmain.min.cssを読み込む
		wp_enqueue_script( 'custom-scripts', get_template_directory_uri() . '/dist/prod/main.min.js', array(), $js_version, true );
		wp_enqueue_style( 'custom-styles', get_template_directory_uri() . '/dist/prod/style.min.css', array(), $css_version );
	} else {
		// 開発環境ではmain.jsとmain.cssを読み込む
		wp_enqueue_script( 'custom-scripts', get_template_directory_uri() . '/dist/dev/main.js', array(), $js_version, true );
		wp_enqueue_style( 'custom-styles', get_template_directory_uri() . '/dist/dev/style.css', array(), $css_version );
	}
}
add_action( 'wp_enqueue_scripts', 'tanren_enqueue_custom_assets' );


// Noto Serif JP（Google Fonts）の読み込み - フロントエンドとエディタ両方に適用
function tanren_enqueue_fonts() {
	wp_enqueue_style(
		'noto-serif-jp',
		'https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@400;700&display=swap',
		[],
		null
	);
}
add_action( 'enqueue_block_assets', 'tanren_enqueue_fonts' );



// 管理画面独自のスタイル操作を/admin/style.cssファイルで有効にする
function tanren_add_admin_style() {
	$path_css = get_template_directory_uri() . '/admin/style.css';
	wp_enqueue_style( 'admin_style', $path_css );
}
add_action( 'admin_enqueue_scripts', 'tanren_add_admin_style' );

// エディタにフロントエンドのスタイルを読み込む（ブロックエディタ iframe 内への注入）
function tanren_add_editor_styles() {
	if ( defined( 'WP_ENV' ) && WP_ENV === 'production' ) {
		add_editor_style( get_template_directory_uri() . '/dist/prod/style.min.css' );
	} else {
		add_editor_style( get_template_directory_uri() . '/dist/dev/style.css' );
	}
}
add_action( 'after_setup_theme', 'tanren_add_editor_styles' );


// 記事の自動整形を無効化
remove_filter( 'the_content', 'wpautop' );

// 抜粋の自動整形を無効化
remove_filter( 'the_excerpt', 'wpautop' );

// ページslug名を<body>のクラスに追加する
function tanren_add_page_slug_to_the_body( $classes ) {
	global $post;

	// もし検索結果ページであれば、クラスの追加を回避する
	if ( is_search() ) {
		return $classes;
	}

	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '__' . $post->post_name;
	}

	return $classes;
}
add_filter( 'body_class', 'tanren_add_page_slug_to_the_body' );

//アイキャッチ画像を設定
add_theme_support( 'post-thumbnails' );
