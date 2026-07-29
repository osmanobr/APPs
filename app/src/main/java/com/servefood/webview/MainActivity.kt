package com.servefood.webview

import android.annotation.SuppressLint
import android.graphics.Bitmap
import android.os.Bundle
import android.view.MenuItem
import android.view.View
import android.webkit.*
import android.widget.Toast
import androidx.appcompat.app.ActionBarDrawerToggle
import androidx.appcompat.app.AppCompatActivity
import androidx.appcompat.widget.Toolbar
import androidx.core.view.GravityCompat
import androidx.drawerlayout.widget.DrawerLayout
import com.google.android.material.navigation.NavigationView

class MainActivity : AppCompatActivity(), NavigationView.OnNavigationItemSelectedListener {

    companion object {
        private const val BASE_URL = "https://servefood.com.br"
    }

    private lateinit var drawerLayout: DrawerLayout
    private lateinit var webView: WebView
    private lateinit var progressBar: View
    private lateinit var navigationView: NavigationView

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        val toolbar = findViewById<Toolbar>(R.id.toolbar)
        setSupportActionBar(toolbar)
        supportActionBar?.apply {
            title = "ServeFood"
            setDisplayHomeAsUpEnabled(true)
        }

        drawerLayout = findViewById(R.id.drawer_layout)
        navigationView = findViewById(R.id.nav_view)
        webView = findViewById(R.id.webview)
        progressBar = findViewById(R.id.progress_bar)

        val toggle = ActionBarDrawerToggle(
            this, drawerLayout, toolbar,
            R.string.nav_open, R.string.nav_close
        )
        drawerLayout.addDrawerListener(toggle)
        toggle.syncState()

        navigationView.setNavigationItemSelectedListener(this)
        setupWebView()

        savedInstanceState ?: run {
            loadModule("/app/login/")
        }
    }

    @SuppressLint("SetJavaScriptEnabled")
    private fun setupWebView() {
        webView.apply {
            settings.apply {
                javaScriptEnabled = true
                domStorageEnabled = true
                databaseEnabled = true
                allowFileAccess = false
                allowContentAccess = false
                mixedContentMode = WebSettings.MIXED_CONTENT_NEVER_ALLOW
                userAgentString = settings.userAgentString + " ServeFood-Android/1.0"
                cacheMode = WebSettings.LOAD_DEFAULT
            }

            CookieManager.getInstance().setAcceptCookie(true)
            CookieManager.getInstance().setAcceptThirdPartyCookies(this@apply, true)

            webViewClient = object : WebViewClient() {
                override fun onPageStarted(view: WebView?, url: String?, favicon: Bitmap?) {
                    progressBar.visibility = View.VISIBLE
                }

                override fun onPageFinished(view: WebView?, url: String?) {
                    progressBar.visibility = View.GONE
                    url?.let { injectSessionSync(it) }
                }

                override fun shouldOverrideUrlLoading(view: WebView?, request: WebResourceRequest?): Boolean {
                    return false
                }

                override fun onReceivedHttpError(
                    view: WebView?, request: WebResourceRequest?, errorResponse: WebResourceResponse?
                ) {
                    if (errorResponse?.statusCode == 401 || errorResponse?.statusCode == 403) {
                        loadModule("/app/login/")
                    }
                }
            }

            webChromeClient = object : WebChromeClient() {
                override fun onReceivedTitle(view: WebView?, title: String?) {
                    supportActionBar?.subtitle = title?.take(40)
                }
            }
        }
    }

    private fun injectSessionSync(url: String) {
        if (url.contains("/app/")) {
            val js = """
                (function() {
                    if (window.sfSynced) return;
                    window.sfSynced = true;
                    try {
                        var token = localStorage.getItem('fs_token');
                        var user = localStorage.getItem('fs_user');
                        if (token && user) {
                            Android.onSessionReady(token, user);
                        }
                    } catch(e) {}
                })();
            """.trimIndent()
            webView.evaluateJavascript(js, null)
        }
    }

    fun loadModule(path: String) {
        val url = BASE_URL + path
        webView.loadUrl(url)
        drawerLayout.closeDrawer(GravityCompat.START)
    }

    override fun onNavigationItemSelected(item: MenuItem): Boolean {
        navigationView.setCheckedItem(item.itemId)
        supportActionBar?.subtitle = item.title

        when (item.itemId) {
            R.id.nav_home -> loadModule("/app/login/")
            R.id.nav_gerente -> loadModule("/app/gerente/")
            R.id.nav_caixa -> loadModule("/app/caixa/")
            R.id.nav_cozinha -> loadModule("/app/cozinha/")
            R.id.nav_bar -> loadModule("/app/bar/")
            R.id.nav_garcom -> loadModule("/app/garcom/")
            R.id.nav_pdv -> loadModule("/app/pdv/")
            R.id.nav_venda_rapida -> loadModule("/app/venda_rapida/")
            R.id.nav_balanca -> loadModule("/app/balanca/")
            R.id.nav_balanca_lite -> loadModule("/app/balanca_lite/")
            R.id.nav_comanda_fixa -> loadModule("/app/comanda_fixa/")
            R.id.nav_cliente -> loadModule("/app/cliente/")
            R.id.nav_ecommerce -> loadModule("/app/e-commerce/")
            R.id.nav_entregador -> loadModule("/app/entregador/")
            R.id.nav_superadmin -> loadModule("/app/superadmin/")
            R.id.nav_tv -> loadModule("/app/tv/")
        }
        return true
    }

    override fun onBackPressed() {
        if (drawerLayout.isDrawerOpen(GravityCompat.START)) {
            drawerLayout.closeDrawer(GravityCompat.START)
        } else if (webView.canGoBack()) {
            webView.goBack()
        } else {
            super.onBackPressed()
        }
    }

    override fun onSaveInstanceState(outState: Bundle) {
        super.onSaveInstanceState(outState)
        webView.saveState(outState)
    }

    override fun onRestoreInstanceState(savedInstanceState: Bundle) {
        super.onRestoreInstanceState(savedInstanceState)
        webView.restoreState(savedInstanceState)
    }
}
