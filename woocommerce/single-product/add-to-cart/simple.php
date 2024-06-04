<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined('ABSPATH') || exit;

global $product;

if ( !$product->is_purchasable() ) {
    return;
}

?>

<form class="" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
    <div class="product-actions">
        <div class="product-price">
            <div class="price">
                <?php echo $product->get_price_html(); ?>
            </div>
        </div>
        <div class="product-actions__right">
            <?php
            woocommerce_quantity_input([
               'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
               'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
               'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(),
            ]);
            ?>

            <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>"
                    class="<?php echo !$product->is_in_stock() ? 'disabled' : ''; ?> btn add-to-cart-btn
                    single_add_to_cart_button button
             alt<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"><svg width="19" height="22" viewBox="0 0 19 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.5 4C6.5 3.73093 6.61817 3.23755 7.02139 2.81753C7.4003 2.42283 8.12147 2 9.5 2C10.8785 2 11.5997 2.42283 11.9786 2.81753C12.3818 3.23755 12.5 3.73093 12.5 4H10H9.5H6.5ZM4.5 6V8H6.5V6H9.5H10H12.5V8H14.5V6H16.0713L16.9997 18.5328C16.9942 18.8273 16.9157 19.247 16.7335 19.5634C16.573 19.8419 16.3707 20 16 20H15.9906H15.9812H15.9717H15.9622H15.9526H15.943H15.9334H15.9237H15.914H15.9042H15.8944H15.8846H15.8747H15.8648H15.8548H15.8448H15.8348H15.8247H15.8146H15.8045H15.7943H15.7841H15.7738H15.7635H15.7532H15.7428H15.7324H15.722H15.7115H15.701H15.6904H15.6798H15.6692H15.6585H15.6478H15.6371H15.6263H15.6155H15.6047H15.5938H15.5829H15.572H15.561H15.55H15.5389H15.5278H15.5167H15.5056H15.4944H15.4832H15.472H15.4607H15.4494H15.438H15.4266H15.4152H15.4038H15.3923H15.3808H15.3693H15.3577H15.3461H15.3345H15.3229H15.3112H15.2994H15.2877H15.2759H15.2641H15.2523H15.2404H15.2285H15.2166H15.2046H15.1926H15.1806H15.1686H15.1565H15.1444H15.1323H15.1201H15.1079H15.0957H15.0835H15.0712H15.0589H15.0466H15.0343H15.0219H15.0095H14.9971H14.9846H14.9721H14.9596H14.9471H14.9345H14.922H14.9094H14.8967H14.8841H14.8714H14.8587H14.846H14.8332H14.8204H14.8076H14.7948H14.782H14.7691H14.7562H14.7433H14.7303H14.7174H14.7044H14.6914H14.6783H14.6653H14.6522H14.6391H14.626H14.6129H14.5997H14.5865H14.5733H14.5601H14.5469H14.5336H14.5203H14.507H14.4937H14.4803H14.467H14.4536H14.4402H14.4268H14.4133H14.3999H14.3864H14.3729H14.3594H14.3459H14.3323H14.3188H14.3052H14.2916H14.2779H14.2643H14.2507H14.237H14.2233H14.2096H14.1959H14.1821H14.1684H14.1546H14.1409H14.1271H14.1132H14.0994H14.0856H14.0717H14.0578H14.044H14.0301H14.0161H14.0022H13.9883H13.9743H13.9603H13.9464H13.9324H13.9184H13.9043H13.8903H13.8763H13.8622H13.8481H13.8341H13.82H13.8059H13.7917H13.7776H13.7635H13.7493H13.7352H13.721H13.7068H13.6926H13.6784H13.6642H13.65H13.6357H13.6215H13.6073H13.593H13.5787H13.5644H13.5502H13.5359H13.5216H13.5073H13.4929H13.4786H13.4643H13.4499H13.4356H13.4212H13.4069H13.3925H13.3781H13.3637H13.3493H13.335H13.3205H13.3061H13.2917H13.2773H13.2629H13.2485H13.234H13.2196H13.2051H13.1907H13.1762H13.1618H13.1473H13.1329H13.1184H13.1039H13.0895H13.075H13.0605H13.046H13.0315H13.017H13.0025H12.9881H12.9736H12.9591H12.9446H12.9301H12.9156H12.9011H12.8866H12.8721H12.8575H12.843H12.8285H12.814H12.7995H12.785H12.7705H12.756H12.7415H12.727H12.7125H12.698H12.6835H12.669H12.6545H12.64H12.6255H12.611H12.5965H12.582H12.5676H12.5531H12.5386H12.5241H12.5097H12.4952H12.4807H12.4663H12.4518H12.4374H12.4229H12.4085H12.394H12.3796H12.3652H12.3507H12.3363H12.3219H12.3075H12.2931H12.2787H12.2643H12.2499H12.2355H12.2212H12.2068H12.1925H12.1781H12.1638H12.1494H12.1351H12.1208H12.1065H12.0922H12.0779H12.0636H12.0493H12.035H12.0208H12.0065H11.9923H11.978H11.9638H11.9496H11.9354H11.9212H11.907H11.8929H11.8787H11.8645H11.8504H11.8363H11.8221H11.808H11.7939H11.7799H11.7658H11.7517H11.7377H11.7236H11.7096H11.6956H11.6816H11.6676H11.6537H11.6397H11.6258H11.6118H11.5979H11.584H11.5701H11.5562H11.5424H11.5285H11.5147H11.5009H11.4871H11.4733H11.4595H11.4458H11.4321H11.4183H11.4046H11.3909H11.3773H11.3636H11.35H11.3364H11.3228H11.3092H11.2956H11.282H11.2685H11.255H11.2415H11.228H11.2146H11.2011H11.1877H11.1743H11.1609H11.1475H11.1342H11.1209H11.1076H11.0943H11.081H11.0678H11.0545H11.0413H11.0281H11.015H11.0018H10.9887H10.9756H10.9625H10.9495H10.9364H10.9234H10.9104H10.8975H10.8845H10.8716H10.8587H10.8458H10.833H10.8202H10.8074H10.7946H10.7818H10.7691H10.7564H10.7437H10.731H10.7184H10.7058H10.6932H10.6806H10.6681H10.6556H10.6431H10.6307H10.6182H10.6058H10.5935H10.5811H10.5688H10.5565H10.5442H10.532H10.5198H10.5076H10.4954H10.4833H10.4712H10.4591H10.447H10.435H10.423H10.4111H10.3991H10.3872H10.3754H10.3635H10.3517H10.3399H10.3282H10.3164H10.3048H10.2931H10.2815H10.2699H10.2583H10.2467H10.2352H10.2238H10.2123H10.2009H10.1895H10.1782H10.1669H10.1556H10.1443H10.1331H10.1219H10.1108H10.0997H10.0886H10.0775H10.0665H10.0555H10.0446H10.0337H10.0228H10.012H10.0011H10H9.99119H9.99037H9.98234H9.97963H9.97344H9.96892H9.9645H9.95826H9.95551H9.94762H9.94649H9.93742H9.93703H9.92831H9.92647H9.91916H9.91594H9.90996H9.90545H9.90073H9.895H9.89145H9.88459H9.88213H9.87421H9.87277H9.86387H9.86337H9.85392H9.85357H9.84444H9.8433H9.83491H9.83307H9.82535H9.82288H9.81574H9.81273H9.8061H9.80261H9.79641H9.79254H9.78668H9.7825H9.77692H9.7725H9.76711H9.76254H9.75727H9.75261H9.74739H9.74273H9.73746H9.73289H9.7275H9.72308H9.7175H9.71332H9.70746H9.70359H9.69739H9.6939H9.68727H9.68426H9.67712H9.67465H9.66693H9.66509H9.6567H9.65556H9.64643H9.64608H9.63663H9.63613H9.62723H9.62579H9.61787H9.61541H9.60855H9.605H9.59927H9.59455H9.59004H9.58406H9.58084H9.57353H9.57169H9.56297H9.56258H9.55351H9.55238H9.54449H9.54174H9.5355H9.53108H9.52656H9.52037H9.51766H9.50963H9.50881H9.5H9.49886H9.48805H9.47721H9.46633H9.45541H9.44447H9.43348H9.42247H9.41142H9.40033H9.38922H9.37807H9.36688H9.35566H9.34441H9.33313H9.32182H9.31047H9.29909H9.28768H9.27623H9.26476H9.25325H9.24171H9.23014H9.21854H9.20691H9.19525H9.18355H9.17183H9.16008H9.14829H9.13648H9.12463H9.11276H9.10086H9.08893H9.07697H9.06497H9.05296H9.04091H9.02883H9.01673H9.0046H8.99244H8.98025H8.96803H8.95579H8.94352H8.93122H8.9189H8.90655H8.89417H8.88177H8.86934H8.85688H8.8444H8.83189H8.81936H8.8068H8.79421H8.7816H8.76897H8.75631H8.74362H8.73091H8.71818H8.70542H8.69264H8.67984H8.66701H8.65416H8.64128H8.62838H8.61546H8.60252H8.58955H8.57656H8.56355H8.55052H8.53746H8.52438H8.51128H8.49816H8.48502H8.47186H8.45867H8.44547H8.43224H8.419H8.40573H8.39244H8.37914H8.36581H8.35247H8.3391H8.32571H8.31231H8.29889H8.28544H8.27198H8.2585H8.24501H8.23149H8.21796H8.20441H8.19084H8.17725H8.16365H8.15002H8.13639H8.12273H8.10906H8.09537H8.08167H8.06794H8.05421H8.04045H8.02669H8.0129H7.9991H7.98529H7.97146H7.95761H7.94375H7.92988H7.91599H7.90209H7.88817H7.87424H7.8603H7.84634H7.83237H7.81839H7.80439H7.79038H7.77635H7.76232H7.74827H7.73421H7.72014H7.70605H7.69196H7.67785H7.66373H7.6496H7.63546H7.62131H7.60715H7.59297H7.57879H7.5646H7.55039H7.53618H7.52195H7.50772H7.49348H7.47923H7.46497H7.4507H7.43642H7.42213H7.40783H7.39353H7.37922H7.3649H7.35057H7.33624H7.32189H7.30754H7.29319H7.27882H7.26445H7.25007H7.23569H7.2213H7.2069H7.1925H7.17809H7.16368H7.14926H7.13484H7.12041H7.10597H7.09153H7.07709H7.06264H7.04819H7.03373H7.01927H7.00481H6.99034H6.97587H6.96139H6.94692H6.93244H6.91795H6.90346H6.88898H6.87449H6.85999H6.8455H6.831H6.8165H6.802H6.7875H6.773H6.75849H6.74399H6.72948H6.71498H6.70047H6.68597H6.67146H6.65696H6.64245H6.62795H6.61344H6.59894H6.58444H6.56993H6.55544H6.54094H6.52644H6.51195H6.49745H6.48296H6.46847H6.45399H6.4395H6.42502H6.41055H6.39607H6.3816H6.36713H6.35267H6.33821H6.32375H6.3093H6.29486H6.28041H6.26597H6.25154H6.23711H6.22269H6.20827H6.19386H6.17945H6.16505H6.15065H6.13627H6.12188H6.10751H6.09314H6.07877H6.06442H6.05007H6.03573H6.02139H6.00707H5.99275H5.97844H5.96413H5.94984H5.93555H5.92128H5.90701H5.89275H5.8785H5.86426H5.85003H5.8358H5.82159H5.80739H5.7932H5.77902H5.76484H5.75068H5.73653H5.72239H5.70826H5.69415H5.68004H5.66595H5.65187H5.6378H5.62374H5.60969H5.59566H5.58164H5.56763H5.55363H5.53965H5.52568H5.51173H5.49779H5.48386H5.46994H5.45604H5.44216H5.42829H5.41443H5.40059H5.38676H5.37295H5.35915H5.34537H5.3316H5.31785H5.30412H5.2904H5.2767H5.26301H5.24934H5.23569H5.22205H5.20843H5.19483H5.18125H5.16768H5.15413H5.1406H5.12709H5.1136H5.10012H5.08666H5.07322H5.0598H5.0464H5.03302H5.01966H5.00631H4.99299H4.97969H4.9664H4.95314H4.9399H4.92668H4.91347H4.90029H4.88713H4.87399H4.86088H4.84778H4.83471H4.82166H4.80863H4.79562H4.78263H4.76967H4.75673H4.74381H4.73092H4.71804H4.7052H4.69237H4.67957H4.66679H4.65404H4.64131H4.62861H4.61593H4.60327H4.59064H4.57803H4.56545H4.5529H4.54037H4.52786H4.51539H4.50293H4.49051H4.47811H4.46574H4.45339H4.44107H4.42878H4.41651H4.40427H4.39206H4.37988H4.36772H4.35559H4.34349H4.33142H4.31938H4.30737H4.29538H4.28342H4.2715H4.2596H4.24773H4.23589H4.22408H4.2123H4.20056H4.18884H4.17715H4.16549H4.15386H4.14227H4.1307H4.11917H4.10767H4.0962H4.08476H4.07335H4.06198H4.05063H4.03932H4.02805H4.0168H4.00559H3.99441H3.98327H3.97216H3.96108H3.95003H3.93902H3.92804H3.9171H3.90619H3.89532H3.88448H3.87368H3.86291H3.85218H3.84148H3.83082H3.82019H3.8096H3.79905H3.78853H3.77804H3.7676H3.75719H3.74682H3.73648H3.72619H3.71593H3.7057H3.69552H3.68537H3.67526H3.66519H3.65516H3.64517H3.63521H3.6253H3.61542H3.60558H3.59578H3.58602H3.5763H3.56663H3.55699H3.54739H3.53783H3.52831H3.51883H3.5094H3.5C3.12935 20 2.92697 19.8419 2.76653 19.5634C2.58433 19.247 2.50578 18.8273 2.50031 18.5328L3.42867 6H4.5ZM4.5 4C4.5 3.26907 4.78183 2.26245 5.57861 1.43247C6.3997 0.577168 7.67853 0 9.5 0C11.3215 0 12.6003 0.577168 13.4214 1.43247C14.2182 2.26245 14.5 3.26907 14.5 4H17H17.9287L17.9973 4.92613L18.9973 18.4261L19 18.463V18.5C19 19.0379 18.8774 19.8482 18.4665 20.5616C18.027 21.3247 17.2293 22 16 22H15.9906H15.9812H15.9717H15.9622H15.9526H15.943H15.9334H15.9237H15.914H15.9042H15.8944H15.8846H15.8747H15.8648H15.8548H15.8448H15.8348H15.8247H15.8146H15.8045H15.7943H15.7841H15.7738H15.7635H15.7532H15.7428H15.7324H15.722H15.7115H15.701H15.6904H15.6798H15.6692H15.6585H15.6478H15.6371H15.6263H15.6155H15.6047H15.5938H15.5829H15.572H15.561H15.55H15.5389H15.5278H15.5167H15.5056H15.4944H15.4832H15.472H15.4607H15.4494H15.438H15.4266H15.4152H15.4038H15.3923H15.3808H15.3693H15.3577H15.3461H15.3345H15.3229H15.3112H15.2994H15.2877H15.2759H15.2641H15.2523H15.2404H15.2285H15.2166H15.2046H15.1926H15.1806H15.1686H15.1565H15.1444H15.1323H15.1201H15.1079H15.0957H15.0835H15.0712H15.0589H15.0466H15.0343H15.0219H15.0095H14.9971H14.9846H14.9721H14.9596H14.9471H14.9345H14.922H14.9094H14.8967H14.8841H14.8714H14.8587H14.846H14.8332H14.8204H14.8076H14.7948H14.782H14.7691H14.7562H14.7433H14.7303H14.7174H14.7044H14.6914H14.6783H14.6653H14.6522H14.6391H14.626H14.6129H14.5997H14.5865H14.5733H14.5601H14.5469H14.5336H14.5203H14.507H14.4937H14.4803H14.467H14.4536H14.4402H14.4268H14.4133H14.3999H14.3864H14.3729H14.3594H14.3459H14.3323H14.3188H14.3052H14.2916H14.2779H14.2643H14.2507H14.237H14.2233H14.2096H14.1959H14.1821H14.1684H14.1546H14.1409H14.1271H14.1132H14.0994H14.0856H14.0717H14.0578H14.044H14.0301H14.0161H14.0022H13.9883H13.9743H13.9603H13.9464H13.9324H13.9184H13.9043H13.8903H13.8763H13.8622H13.8481H13.8341H13.82H13.8059H13.7917H13.7776H13.7635H13.7493H13.7352H13.721H13.7068H13.6926H13.6784H13.6642H13.65H13.6357H13.6215H13.6073H13.593H13.5787H13.5644H13.5502H13.5359H13.5216H13.5073H13.4929H13.4786H13.4643H13.4499H13.4356H13.4212H13.4069H13.3925H13.3781H13.3637H13.3493H13.335H13.3205H13.3061H13.2917H13.2773H13.2629H13.2485H13.234H13.2196H13.2051H13.1907H13.1762H13.1618H13.1473H13.1329H13.1184H13.1039H13.0895H13.075H13.0605H13.046H13.0315H13.017H13.0025H12.9881H12.9736H12.9591H12.9446H12.9301H12.9156H12.9011H12.8866H12.8721H12.8575H12.843H12.8285H12.814H12.7995H12.785H12.7705H12.756H12.7415H12.727H12.7125H12.698H12.6835H12.669H12.6545H12.64H12.6255H12.611H12.5965H12.582H12.5676H12.5531H12.5386H12.5241H12.5097H12.4952H12.4807H12.4663H12.4518H12.4374H12.4229H12.4085H12.394H12.3796H12.3652H12.3507H12.3363H12.3219H12.3075H12.2931H12.2787H12.2643H12.2499H12.2355H12.2212H12.2068H12.1925H12.1781H12.1638H12.1494H12.1351H12.1208H12.1065H12.0922H12.0779H12.0636H12.0493H12.035H12.0208H12.0065H11.9923H11.978H11.9638H11.9496H11.9354H11.9212H11.907H11.8929H11.8787H11.8645H11.8504H11.8363H11.8221H11.808H11.7939H11.7799H11.7658H11.7517H11.7377H11.7236H11.7096H11.6956H11.6816H11.6676H11.6537H11.6397H11.6258H11.6118H11.5979H11.584H11.5701H11.5562H11.5424H11.5285H11.5147H11.5009H11.4871H11.4733H11.4595H11.4458H11.4321H11.4183H11.4046H11.3909H11.3773H11.3636H11.35H11.3364H11.3228H11.3092H11.2956H11.282H11.2685H11.255H11.2415H11.228H11.2146H11.2011H11.1877H11.1743H11.1609H11.1475H11.1342H11.1209H11.1076H11.0943H11.081H11.0678H11.0545H11.0413H11.0281H11.015H11.0018H10.9887H10.9756H10.9625H10.9495H10.9364H10.9234H10.9104H10.8975H10.8845H10.8716H10.8587H10.8458H10.833H10.8202H10.8074H10.7946H10.7818H10.7691H10.7564H10.7437H10.731H10.7184H10.7058H10.6932H10.6806H10.6681H10.6556H10.6431H10.6307H10.6182H10.6058H10.5935H10.5811H10.5688H10.5565H10.5442H10.532H10.5198H10.5076H10.4954H10.4833H10.4712H10.4591H10.447H10.435H10.423H10.4111H10.3991H10.3872H10.3754H10.3635H10.3517H10.3399H10.3282H10.3164H10.3048H10.2931H10.2815H10.2699H10.2583H10.2467H10.2352H10.2238H10.2123H10.2009H10.1895H10.1782H10.1669H10.1556H10.1443H10.1331H10.1219H10.1108H10.0997H10.0886H10.0775H10.0665H10.0555H10.0446H10.0337H10.0228H10.012H10.0011H10H9.99119H9.99037H9.98234H9.97963H9.97344H9.96892H9.9645H9.95826H9.95551H9.94762H9.94649H9.93742H9.93703H9.92831H9.92647H9.91916H9.91594H9.90996H9.90545H9.90073H9.895H9.89145H9.88459H9.88213H9.87421H9.87277H9.86387H9.86337H9.85392H9.85357H9.84444H9.8433H9.83491H9.83307H9.82535H9.82288H9.81574H9.81273H9.8061H9.80261H9.79641H9.79254H9.78668H9.7825H9.77692H9.7725H9.76711H9.76254H9.75727H9.75261H9.74739H9.74273H9.73746H9.73289H9.7275H9.72308H9.7175H9.71332H9.70746H9.70359H9.69739H9.6939H9.68727H9.68426H9.67712H9.67465H9.66693H9.66509H9.6567H9.65556H9.64643H9.64608H9.63663H9.63613H9.62723H9.62579H9.61787H9.61541H9.60855H9.605H9.59927H9.59455H9.59004H9.58406H9.58084H9.57353H9.57169H9.56297H9.56258H9.55351H9.55238H9.54449H9.54174H9.5355H9.53108H9.52656H9.52037H9.51766H9.50963H9.50881H9.5H9.49886H9.48805H9.47721H9.46633H9.45541H9.44447H9.43348H9.42247H9.41142H9.40033H9.38922H9.37807H9.36688H9.35566H9.34441H9.33313H9.32182H9.31047H9.29909H9.28768H9.27623H9.26476H9.25325H9.24171H9.23014H9.21854H9.20691H9.19525H9.18355H9.17183H9.16008H9.14829H9.13648H9.12463H9.11276H9.10086H9.08893H9.07697H9.06497H9.05296H9.04091H9.02883H9.01673H9.0046H8.99244H8.98025H8.96803H8.95579H8.94352H8.93122H8.9189H8.90655H8.89417H8.88177H8.86934H8.85688H8.8444H8.83189H8.81936H8.8068H8.79421H8.7816H8.76897H8.75631H8.74362H8.73091H8.71818H8.70542H8.69264H8.67984H8.66701H8.65416H8.64128H8.62838H8.61546H8.60252H8.58955H8.57656H8.56355H8.55052H8.53746H8.52438H8.51128H8.49816H8.48502H8.47186H8.45867H8.44547H8.43224H8.419H8.40573H8.39244H8.37914H8.36581H8.35247H8.3391H8.32571H8.31231H8.29889H8.28544H8.27198H8.2585H8.24501H8.23149H8.21796H8.20441H8.19084H8.17725H8.16365H8.15002H8.13639H8.12273H8.10906H8.09537H8.08167H8.06794H8.05421H8.04045H8.02669H8.0129H7.9991H7.98529H7.97146H7.95761H7.94375H7.92988H7.91599H7.90209H7.88817H7.87424H7.8603H7.84634H7.83237H7.81839H7.80439H7.79038H7.77635H7.76232H7.74827H7.73421H7.72014H7.70605H7.69196H7.67785H7.66373H7.6496H7.63546H7.62131H7.60715H7.59297H7.57879H7.5646H7.55039H7.53618H7.52195H7.50772H7.49348H7.47923H7.46497H7.4507H7.43642H7.42213H7.40783H7.39353H7.37922H7.3649H7.35057H7.33624H7.32189H7.30754H7.29319H7.27882H7.26445H7.25007H7.23569H7.2213H7.2069H7.1925H7.17809H7.16368H7.14926H7.13484H7.12041H7.10597H7.09153H7.07709H7.06264H7.04819H7.03373H7.01927H7.00481H6.99034H6.97587H6.96139H6.94692H6.93244H6.91795H6.90346H6.88898H6.87449H6.85999H6.8455H6.831H6.8165H6.802H6.7875H6.773H6.75849H6.74399H6.72948H6.71498H6.70047H6.68597H6.67146H6.65696H6.64245H6.62795H6.61344H6.59894H6.58444H6.56993H6.55544H6.54094H6.52644H6.51195H6.49745H6.48296H6.46847H6.45399H6.4395H6.42502H6.41055H6.39607H6.3816H6.36713H6.35267H6.33821H6.32375H6.3093H6.29486H6.28041H6.26597H6.25154H6.23711H6.22269H6.20827H6.19386H6.17945H6.16505H6.15065H6.13627H6.12188H6.10751H6.09314H6.07877H6.06442H6.05007H6.03573H6.02139H6.00707H5.99275H5.97844H5.96413H5.94984H5.93555H5.92128H5.90701H5.89275H5.8785H5.86426H5.85003H5.8358H5.82159H5.80739H5.7932H5.77902H5.76484H5.75068H5.73653H5.72239H5.70826H5.69415H5.68004H5.66595H5.65187H5.6378H5.62374H5.60969H5.59566H5.58164H5.56763H5.55363H5.53965H5.52568H5.51173H5.49779H5.48386H5.46994H5.45604H5.44216H5.42829H5.41443H5.40059H5.38676H5.37295H5.35915H5.34537H5.3316H5.31785H5.30412H5.2904H5.2767H5.26301H5.24934H5.23569H5.22205H5.20843H5.19483H5.18125H5.16768H5.15413H5.1406H5.12709H5.1136H5.10012H5.08666H5.07322H5.0598H5.0464H5.03302H5.01966H5.00631H4.99299H4.97969H4.9664H4.95314H4.9399H4.92668H4.91347H4.90029H4.88713H4.87399H4.86088H4.84778H4.83471H4.82166H4.80863H4.79562H4.78263H4.76967H4.75673H4.74381H4.73092H4.71804H4.7052H4.69237H4.67957H4.66679H4.65404H4.64131H4.62861H4.61593H4.60327H4.59064H4.57803H4.56545H4.5529H4.54037H4.52786H4.51539H4.50293H4.49051H4.47811H4.46574H4.45339H4.44107H4.42878H4.41651H4.40427H4.39206H4.37988H4.36772H4.35559H4.34349H4.33142H4.31938H4.30737H4.29538H4.28342H4.2715H4.2596H4.24773H4.23589H4.22408H4.2123H4.20056H4.18884H4.17715H4.16549H4.15386H4.14227H4.1307H4.11917H4.10767H4.0962H4.08476H4.07335H4.06198H4.05063H4.03932H4.02805H4.0168H4.00559H3.99441H3.98327H3.97216H3.96108H3.95003H3.93902H3.92804H3.9171H3.90619H3.89532H3.88448H3.87368H3.86291H3.85218H3.84148H3.83082H3.82019H3.8096H3.79905H3.78853H3.77804H3.7676H3.75719H3.74682H3.73648H3.72619H3.71593H3.7057H3.69552H3.68537H3.67526H3.66519H3.65516H3.64517H3.63521H3.6253H3.61542H3.60558H3.59578H3.58602H3.5763H3.56663H3.55699H3.54739H3.53783H3.52831H3.51883H3.5094H3.5C2.27065 22 1.47303 21.3247 1.03347 20.5616C0.622555 19.8482 0.5 19.0379 0.5 18.5V18.463L0.502732 18.4261L1.50273 4.92613L1.57133 4H2.5H4.5Z" fill="white"></path>
                </svg><?php echo $product->is_in_stock() ? esc_html($product->single_add_to_cart_text())
                    : __('SOLD OUT', DOMAIN); ?></button>
        </div>
        <?php get_template_part('template-parts/product/gifts'); ?>
    </div>

</form>