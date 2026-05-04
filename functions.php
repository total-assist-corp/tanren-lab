<?php
// コンパイルファイルの読み込み
function enqueue_custom_assets() {
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
add_action( 'wp_enqueue_scripts', 'enqueue_custom_assets' );


// Adobe TypeKitの読み込み
add_action(
	'wp_head',
	function () { ?>
	<script>
		(function (d) {
			var config = {
				kitId: 'att5ijt',
				scriptTimeout: 3000,
				async: true
			},
				h = d.documentElement, t = setTimeout(function () { h.className = h.className.replace(/\bwf-loading\b/g, "") + " wf-inactive"; }, config.scriptTimeout), tk = d.createElement("script"), f = false, s = d.getElementsByTagName("script")[0], a; h.className += " wf-loading"; tk.src = 'https://use.typekit.net/' + config.kitId + '.js'; tk.async = true; tk.onload = tk.onreadystatechange = function () { a = this.readyState; if (f || a && a != "complete" && a != "loaded") return; f = true; clearTimeout(t); try { Typekit.load(config) } catch (e) { } }; s.parentNode.insertBefore(tk, s)
		})(document);
	</script>
<?php }
);



// 管理画面独自のスタイル操作を/admin/style.cssファイルで有効にする
function add_admin_style() {
	$path_css = get_template_directory_uri() . '/admin/style.css';
	wp_enqueue_style( 'admin_style', $path_css );
}
add_action( 'admin_enqueue_scripts', 'add_admin_style' );


// 記事の自動整形を無効化
remove_filter( 'the_content', 'wpautop' );

// 抜粋の自動整形を無効化
remove_filter( 'the_excerpt', 'wpautop' );

// ページslug名を<body>のクラスに追加する
function add_page_slug_to_the_body( $classes ) {
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
add_filter( 'body_class', 'add_page_slug_to_the_body' );

//アイキャッチ画像を設定
add_theme_support( 'post-thumbnails' );

// 管理画面で「外観 > カスタマイズ」を表示
add_action( 'admin_menu', function () {
	add_theme_page( __( 'Customizer', 'your-textdomain' ), __( 'カスタマイズ', 'your-textdomain' ), 'edit_theme_options', 'customize.php' );
} );
