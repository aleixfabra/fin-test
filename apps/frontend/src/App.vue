<template>
  <header class="center">
    <img alt="logo" src="@/assets/logo.svg" />
    <nav>
      <ul class="inline">
        <li><router-link to="/">Portfolio #1</router-link></li>
        <li><router-link to="/about">About</router-link></li>
      </ul>
    </nav>
  </header>
  <router-view />
</template>

<style lang="scss">
$base-color: #2c3e50;
$primary-color: #118afa;
$primary-color-dark: #006dd2;
$white: #ffffff;
$black: #000000;
$gray: #808080;

*,
::after,
::before {
  box-sizing: border-box;
}

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  color: $base-color;
  max-width: 800px;
  margin: auto;
  padding-bottom: 60px;
}

a {
  color: $primary-color;
}

ul {
  list-style: none;
  &.inline {
    li {
      display: inline-block;
      margin: 0 10px;
    }
  }
}

header {
  margin: 60px 0 80px;
  nav {
    position: absolute;
    top: 15px;
    right: 15px;
  }
}

table {
  table-layout: fixed;
  width: 100%;
  text-align: end;
  margin: 50px 0;
  border-spacing: 0;
  th {
    border-bottom: 1px solid;
  }
  th,
  td {
    padding: 10px 0;
  }
}

button {
  padding: 6px 10px;
  border-radius: 2px;
  font-weight: bold;
  cursor: pointer;
  border: 1px solid;
  &.primary {
    color: $white;
    background-color: $primary-color;
    border-color: $primary-color;
    &:hover {
      color: $primary-color;
      background-color: $white;
      border-color: $primary-color;
    }
  }
  &.outline-primary {
    color: $primary-color;
    background-color: $white;
    border-color: $primary-color;
    &:hover {
      color: $white;
      background-color: $primary-color;
    }
    &.active {
      color: $white;
      background-color: $primary-color-dark;
      border-color: $primary-color-dark;
    }
  }
}

form {
  margin-bottom: 20px;
  .form-group {
    margin-bottom: 15px;
  }
  input,
  select {
    display: block;
    width: 100%;
    padding: 5px;
  }
}

.center {
  text-align: center;
}

.modal {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background: #{$black}6e;
  .modal-content {
    position: relative;
    background-color: $white;
    margin: 1.75rem auto;
    max-width: 500px;
    opacity: 1;
    .modal-header {
      padding: 15px;
      border-bottom: 1px solid $gray;
    }
    .modal-body {
      padding: 15px;
    }
  }
  .close-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 4px 5px;
    line-height: 10px;
  }
}
</style>
