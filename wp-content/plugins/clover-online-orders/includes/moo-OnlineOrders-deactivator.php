<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://zaytechapps.com
 * @since      1.0.0
 *
 * @package    Moo_OnlineOrders
 * @subpackage Moo_OnlineOrders/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Moo_OnlineOrders
 * @subpackage Moo_OnlineOrders/includes
 * @author     Mohammed EL BANYAOUI
 */
class Moo_OnlineOrders_Deactivator {
	/**
	 * @since    1.0.0
	 */
	public static function deactivate() {
    }
	public static function deactivateAndClean() {
         global $wpdb;
         //-- Table `item_option`--
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_item_option` ;");

         //-- Table `item_tax_rate` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_item_tax_rate` ;");

         // -- Table `modifier_group` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_item_order` ;");

         //*-- Table `item_tag` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_item_tag` ;");

         //-- Table `item_modifier_group` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_item_modifier_group` ;");

         //-- Table `order_types --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_images` ;");

         //-- Table `item` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_item` ;");

         //-- Table `orders` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_order` ;");

         //-- Table `option`--
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_option` ;");

         //-- Table `tag` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_tag` ;");

         //-- Table `tax_rate` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_tax_rate` ;");

         //-- Table `modifier` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_modifier` ;");

         //-- Table `category` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_category` ;");

         //-- Table `attribute` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_attribute` ;");

         //-- Table `item_group` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_item_group` ;");

         //-- Table `modifier_group` --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_modifier_group` ;");

         //-- Table `order_types --
         $wpdb->query("DROP TABLE IF EXISTS `{$wpdb->prefix}moo_order_types` ;");

    }
	public static function onlyClean() {
         global $wpdb;
         //-- Table `item_option`--
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_item_option` ;");

         //-- Table `item_tax_rate` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_item_tax_rate` ;");

         // -- Table `modifier_group` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_item_order` ;");

         //*-- Table `item_tag` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_item_tag` ;");

         //-- Table `item_modifier_group` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_item_modifier_group` ;");

         //-- Table `order_types --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_images` ;");

         //-- Table `item` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_item` ;");

         //-- Table `orders` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_order` ;");

         //-- Table `option`--
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_option` ;");

         //-- Table `tag` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_tag` ;");

         //-- Table `tax_rate` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_tax_rate` ;");

         //-- Table `modifier` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_modifier` ;");

         //-- Table `category` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_category` ;");

         //-- Table `attribute` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_attribute` ;");

         //-- Table `item_group` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_item_group` ;");

         //-- Table `modifier_group` --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_modifier_group` ;");

         //-- Table `order_types --
         $wpdb->query("DELETE FROM `{$wpdb->prefix}moo_order_types` ;");

    }

}
