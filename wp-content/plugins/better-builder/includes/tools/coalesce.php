<?php

function better_coalesce() {
  $args = func_get_args();
  foreach ( $args as $arg ) {
    if ( !empty( $arg ) ) {
      return $arg;
    }
  }
  return $args[0];
}

function better_coalesce_isset() {
  $args = func_get_args();
  foreach ( $args as $arg ) {
    if ( isset( $arg ) ) {
      return $arg;
    }
  }
  return $args[0];
}
