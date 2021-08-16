<template>
  <v-dialog
    v-model="dialog"
    persistent
    max-width="600"
    @keydown.esc="dialog = false"
  >
    <v-card
      :loading="loading"
    >
      <template slot="progress">
        <v-progress-linear
          color="secondary"
          height="10"
          indeterminate
        ></v-progress-linear>
      </template>
      <v-toolbar dense color="secondary">
        <v-toolbar-title class="white--text">Bienvenido</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn
          icon
          @click.stop="dialog = false"
        >
          <v-icon color="white">
            mdi-close
          </v-icon>
        </v-btn>
      </v-toolbar>
      <div class="px-5 pb-5">
        <validation-observer ref="loginObserver" v-slot="{ invalid }">
          <form v-on:submit.prevent="login">
            <v-card-text>
              <validation-provider
                v-slot="{ errors }"
                name="username"
                rules="required|min:3|alpha_num"
              >
                <v-text-field
                  label="Usuario"
                  v-model="loginForm.username"
                  data-vv-name="username"
                  :error-messages="errors"
                  prepend-icon="mdi-account"
                  autofocus
                ></v-text-field>
              </validation-provider>
              <validation-provider
                v-slot="{ errors }"
                name="password"
                rules="required|min:4"
              >
                <v-text-field
                  label="Contraseña"
                  v-model="loginForm.password"
                  data-vv-name="password"
                  :error-messages="errors"
                  prepend-icon="mdi-lock"
                  :append-icon="shadowPassword ? 'mdi-eye' : 'mdi-eye-off'"
                  @click:append="() => (shadowPassword = !shadowPassword)"
                  :type="shadowPassword ? 'password' : 'text'"
                ></v-text-field>
              </validation-provider>
            </v-card-text>
            <v-card-actions>
              <v-btn
                block
                type="submit"
                color="primary"
                :disabled="invalid || loading"
              >
                Ingresar
              </v-btn>
            </v-card-actions>
          </form>
        </validation-observer>
      </div>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'Login',
  data: function() {
    return {
      dialog: false,
      shadowPassword: true,
      loginForm: {
        username: '',
        password: '',
      },
      loading: false,
    }
  },
  methods: {
    showDialog() {
      this.loginForm = {
        username: '',
        password: '',
      }
      this.dialog = true
      this.$nextTick(() => {
        this.$refs.loginObserver.reset()
      })
    },
    async login() {
      try {
        let valid = await this.$refs.loginObserver.validate()
        if (valid) {
          this.loading = true
          await axios.get('sanctum/csrf-cookie')
          await this.$store.dispatch('login', this.loginForm)
          this.$router.push({
            name: this.$store.getters.user.isAdmin ? 'users' : 'procedures'
          })
        }
      } catch(error) {
        this.loginForm.password = ''
        this.$refs.loginObserver.reset()
        if ('errors' in error.response.data) {
          this.$refs.loginObserver.setErrors(error.response.data.errors)
        }
      } finally {
        this.loading = false
      }
    },
  },
}
</script>