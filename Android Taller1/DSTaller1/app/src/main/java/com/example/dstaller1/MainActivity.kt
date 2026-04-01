package com.example.dstaller1

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import androidx.activity.ComponentActivity
import org.json.JSONObject
import java.io.BufferedReader
import java.io.BufferedWriter
import java.io.InputStreamReader
import java.io.OutputStreamWriter
import java.net.HttpURLConnection
import java.net.URL
import java.net.URLEncoder

class MainActivity : ComponentActivity() {

    private lateinit var etNombre: EditText
    private lateinit var etPrecio: EditText
    private lateinit var etDetalle: EditText
    private lateinit var btnGuardar: Button
    private lateinit var tvResultado: TextView

    // Para emulador Android Studio
    private val urlServidor = "http://10.0.2.2/tal12026/Web/BACKEND/PHP/insert_producto.php"

    // Para celular físico sería algo así:
    // private val urlServidor = "http://192.168.1.10/ecommerce/insert_producto.php"

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        etNombre = findViewById(R.id.etNombre)
        etPrecio = findViewById(R.id.etPrecio)
        etDetalle = findViewById(R.id.etDetalle)
        btnGuardar = findViewById(R.id.btnGuardar)
        tvResultado = findViewById(R.id.tvResultado)

        btnGuardar.setOnClickListener {
            guardarProducto()
        }
    }

    private fun guardarProducto() {
        val nombre = etNombre.text.toString().trim()
        val precio = etPrecio.text.toString().trim()
        val detalle = etDetalle.text.toString().trim()

        if (nombre.isEmpty() || precio.isEmpty() || detalle.isEmpty()) {
            Toast.makeText(this, "Todos los campos son obligatorios", Toast.LENGTH_SHORT).show()
            tvResultado.text = "Error: completa todos los campos."
            return
        }

        Thread {
            var conexion: HttpURLConnection? = null

            try {
                val url = URL(urlServidor)
                conexion = url.openConnection() as HttpURLConnection
                conexion.requestMethod = "POST"
                conexion.doOutput = true
                conexion.setRequestProperty("Content-Type", "application/x-www-form-urlencoded")

                val datos = "nombre=" + URLEncoder.encode(nombre, "UTF-8") +
                        "&precio=" + URLEncoder.encode(precio, "UTF-8") +
                        "&detalle=" + URLEncoder.encode(detalle, "UTF-8")

                val writer = BufferedWriter(
                    OutputStreamWriter(conexion.outputStream, "UTF-8")
                )
                writer.write(datos)
                writer.flush()
                writer.close()

                val reader = BufferedReader(
                    InputStreamReader(conexion.inputStream)
                )

                val respuesta = StringBuilder()
                var linea: String?

                while (reader.readLine().also { linea = it } != null) {
                    respuesta.append(linea)
                }

                reader.close()

                runOnUiThread {
                    procesarRespuesta(respuesta.toString())
                }

            } catch (e: Exception) {
                runOnUiThread {
                    tvResultado.text = "Error de conexión: ${e.message}"
                    Toast.makeText(
                        this,
                        "No se pudo conectar con el servidor",
                        Toast.LENGTH_LONG
                    ).show()
                }
            } finally {
                conexion?.disconnect()
            }
        }.start()
    }

    private fun procesarRespuesta(respuesta: String) {
        try {
            val json = JSONObject(respuesta)
            val success = json.getBoolean("success")
            val message = json.getString("message")

            tvResultado.text = message
            Toast.makeText(this, message, Toast.LENGTH_SHORT).show()

            if (success) {
                etNombre.setText("")
                etPrecio.setText("")
                etDetalle.setText("")
            }

        } catch (e: Exception) {
            tvResultado.text = "Respuesta inválida del servidor: $respuesta"
            Toast.makeText(this, "Error al procesar la respuesta", Toast.LENGTH_LONG).show()
        }
    }
}