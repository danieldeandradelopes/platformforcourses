<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do MySQL
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/pt-br:Editando_wp-config.php
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define( 'DB_NAME', 'eadwordpress' );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', 'root' );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', '' );

/** Nome do host do MySQL */
define( 'DB_HOST', 'localhost' );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'Dk1v?4L-Yeey|hB=b[yN?mEzsqV%Yv}2^]wGyp)bfekS.i4U?VBw.RiqHZxKT6BB' );
define( 'SECURE_AUTH_KEY',  '4JiQ$GYF4sG%n[,r6f2]yqah})rHv.Bm9oI5V.yW<@{VFK*~rs6yE3EBFS7+jp6?' );
define( 'LOGGED_IN_KEY',    '2`4}c<hhZq=^v/XL,6UgZ[zP*t<4QKX84F{3KN;a)__ML=CowZ{wV!avA337|eI2' );
define( 'NONCE_KEY',        '1iydrz[9xK+?}>p)geo.)K$Re3eHA.IhFvJ$AU+&%iT%v<+n(y%t+p&Z%fW<9.=}' );
define( 'AUTH_SALT',        'Y<UH[Aj?+K(-&&l^ DC^i7eBby]E@CuWd<k[l/+4?xk#aE%0(ExJ3h?1<+DpC|B/' );
define( 'SECURE_AUTH_SALT', 'ITxKpG]](mvtXK7nT.dFy*0Pbh1Ql;.jGTc}WDU7dY [eJhX97mmIV,`AaY{?i&@' );
define( 'LOGGED_IN_SALT',   'L:JKsh5*eYK<2!v/?:>=Jky-w:,QyI5[oXUNztrRJ,X:b%^4NZ.}!H#/ctsroy}c' );
define( 'NONCE_SALT',       ':|ZMDLdtu~&]SxrCT*TNo3uh+Z2o*W:AzF#wZ!A>^B:T&MEV,}E]}5gnh[g(~PfU' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://codex.wordpress.org/pt-br:Depura%C3%A7%C3%A3o_no_WordPress
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Configura as variáveis e arquivos do WordPress. */
require_once(ABSPATH . 'wp-settings.php');
