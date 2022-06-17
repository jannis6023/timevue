<template>

  <router-link
      v-bind="$props"
      custom
      v-slot="{ isActive, href, navigate }"
  >
    <li class="nav-item" :class="hasChildren ? 'children':(isActive ? activeClass : inactiveClass)">
      <a
          v-bind="$attrs"
          :href="href"
          @click="navigate"
          class="nav-link"
      >
        <i v-if="iconName != null" :class="'nav-link-icon d-md-none d-lg-inline-block ti ti-' + iconName" style="font-size: 20px"></i>
        <span class="nav-link-title">
          <slot/>
        </span>
      </a>
    </li>
  </router-link>
</template>

<script>
import { RouterLink } from 'vue-router'

export default {
  name: 'AppLink',
  inheritAttrs: false,

  props: {
    // add @ts-ignore if using TypeScript
    ...RouterLink.props,
    inactiveClass: String,
    iconName: String,
    hasChildren: Boolean,
    childNav: Object
  }
}
</script>