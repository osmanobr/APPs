<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';

class ImportacaoCSV {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Cria um novo registro de importação de arquivo CSV.
     *
     * @param string $nome_arquivo Nome do arquivo original.
     * @param int $total_linhas Número total de linhas de dados no CSV (excluindo cabeçalho).
     * @param string|null $usuario_id ID do usuário que realizou a importação.
     * @return string|false O UUID do registro de importação criado ou false em caso de falha.
     */
    public function criarRegistroImportacao($nome_arquivo, $total_linhas, $usuario_id = null) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO importacoes_csv (id, nome_arquivo, usuario_id, total_linhas, status_importacao, data_importacao)
                    VALUES (:id, :nome_arquivo, :usuario_id, :total_linhas, :status_importacao, CURRENT_TIMESTAMP)";
            $stmt = $this->pdo->prepare($sql);

            $status_inicial = 'pendente'; // Ou 'processando' se o processamento for imediato

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':nome_arquivo', $nome_arquivo);
            $stmt->bindParam(':usuario_id', $usuario_id);
            $stmt->bindParam(':total_linhas', $total_linhas, PDO::PARAM_INT);
            $stmt->bindParam(':status_importacao', $status_inicial);

            if ($stmt->execute()) {
                add_log('info', 'importacao_csv_registrada', "Registro de importação CSV ID {$uuid} para arquivo '{$nome_arquivo}' criado.", $usuario_id);
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'importacao_csv_criar_falha_db', "PDOException ao criar registro de importação CSV para '{$nome_arquivo}': " . $e->getMessage(), $usuario_id);
            error_log("Erro ao criar registro de importação CSV: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Adiciona um detalhe de linha processada (ou falha) para uma importação.
     *
     * @param array $dados Dados do detalhe da baixa:
     *              importacao_id (CHAR(36) - FK para importacoes_csv),
     *              linha_csv (INT - número da linha no arquivo),
     *              identificador_pagamento_csv (VARCHAR - ID do pagamento no CSV),
     *              valor_baixado_csv (DECIMAL - valor no CSV),
     *              data_pagamento_csv (DATE - data no CSV),
     *              status_baixa (ENUM - 'sucesso', 'falha', 'nao_encontrado', 'ja_baixado'),
     *              pagamento_id (CHAR(36) - opcional, ID do pagamento no sistema se encontrado/baixado),
     *              mensagem_erro (TEXT - opcional),
     *              dados_linha_originais (TEXT - JSON da linha original para auditoria)
     * @return string|false O UUID do detalhe criado ou false em caso de falha.
     */
    public function adicionarDetalheBaixa($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO detalhes_baixa_csv (id, importacao_id, linha_csv, identificador_pagamento_csv, valor_baixado_csv, data_pagamento_csv, status_baixa, pagamento_id, mensagem_erro, dados_linha_originais)
                    VALUES (:id, :importacao_id, :linha_csv, :identificador_pagamento_csv, :valor_baixado_csv, :data_pagamento_csv, :status_baixa, :pagamento_id, :mensagem_erro, :dados_linha_originais)";
            $stmt = $this->pdo->prepare($sql);

            $pagamento_id_sistema = $dados['pagamento_id'] ?? null;
            $mensagem_erro = $dados['mensagem_erro'] ?? null;
            $dados_linha_originais = $dados['dados_linha_originais'] ?? null;
            $valor_baixado = isset($dados['valor_baixado_csv']) ? str_replace(',', '.', $dados['valor_baixado_csv']) : null; // Normaliza para decimal

            $data_pag_csv_formatada = null;
            if (!empty($dados['data_pagamento_csv'])) {
                try {
                    // Tenta converter dd/mm/yyyy para yyyy-mm-dd
                    $dateObj = DateTime::createFromFormat('d/m/Y', $dados['data_pagamento_csv']);
                    if ($dateObj) {
                        $data_pag_csv_formatada = $dateObj->format('Y-m-d');
                    } else {
                         // Tentar outros formatos ou deixar null se não reconhecer
                        $dateObjFallback = new DateTime($dados['data_pagamento_csv']);
                        $data_pag_csv_formatada = $dateObjFallback->format('Y-m-d');
                    }
                } catch (Exception $e) {
                    $data_pag_csv_formatada = null; // Deixa null se a data for inválida
                }
            }


            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':importacao_id', $dados['importacao_id']);
            $stmt->bindParam(':linha_csv', $dados['linha_csv'], PDO::PARAM_INT);
            $stmt->bindParam(':identificador_pagamento_csv', $dados['identificador_pagamento_csv']);
            $stmt->bindParam(':valor_baixado_csv', $valor_baixado);
            $stmt->bindParam(':data_pagamento_csv', $data_pag_csv_formatada);
            $stmt->bindParam(':status_baixa', $dados['status_baixa']);
            $stmt->bindParam(':pagamento_id', $pagamento_id_sistema);
            $stmt->bindParam(':mensagem_erro', $mensagem_erro);
            $stmt->bindParam(':dados_linha_originais', $dados_linha_originais);

            if ($stmt->execute()) {
                // Log pode ser muito verboso aqui, talvez logar apenas falhas ou um resumo no final
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            // Não usar add_log aqui para evitar loop em caso de falha no log de logs.
            error_log("Erro ao adicionar detalhe de baixa CSV para Importacao ID {$dados['importacao_id']}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza as estatísticas e o status de um registro de importação.
     *
     * @param string $importacao_id UUID da importação.
     * @param int $linhas_processadas_sucesso
     * @param int $linhas_falha
     * @param string $status_final ('concluido', 'concluido_com_erros', 'falhou')
     * @return bool
     */
    public function finalizarImportacao($importacao_id, $linhas_processadas_sucesso, $linhas_falha, $status_final) {
        try {
            $sql = "UPDATE importacoes_csv
                    SET linhas_processadas_sucesso = :sucesso,
                        linhas_falha = :falha,
                        status_importacao = :status
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':sucesso', $linhas_processadas_sucesso, PDO::PARAM_INT);
            $stmt->bindParam(':falha', $linhas_falha, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status_final);
            $stmt->bindParam(':id', $importacao_id);

            $success = $stmt->execute();
            if ($success) {
                 add_log('info', 'importacao_csv_finalizada', "Importação CSV ID {$importacao_id} finalizada com status '{$status_final}'. Sucesso: {$linhas_processadas_sucesso}, Falhas: {$linhas_falha}.", get_logged_in_user_id());
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'importacao_csv_finalizar_falha_db', "PDOException ao finalizar Importacao CSV ID {$importacao_id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao finalizar importação CSV: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todas as importações realizadas.
     * @return array
     */
    public function getAllImportacoes() {
        try {
            $sql = "SELECT imp.*, u.nome as nome_usuario
                    FROM importacoes_csv imp
                    LEFT JOIN usuarios u ON imp.usuario_id = u.id
                    ORDER BY imp.data_importacao DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar importações CSV: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca os detalhes de uma importação específica.
     * @param string $importacao_id UUID da importação.
     * @return array
     */
    public function getDetalhesByImportacaoId($importacao_id) {
        try {
            $sql = "SELECT det.*, p.origem_tipo, p.origem_id as id_origem_pagamento
                    FROM detalhes_baixa_csv det
                    LEFT JOIN pagamentos p ON det.pagamento_id = p.id
                    WHERE det.importacao_id = :importacao_id
                    ORDER BY det.linha_csv ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':importacao_id', $importacao_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar detalhes da importação CSV ID {$importacao_id}: " . $e->getMessage());
            return [];
        }
    }
     /**
     * Busca uma importação pelo ID.
     * @param string $id UUID da importação.
     * @return array|false
     */
    public function getImportacaoById($id) {
        try {
            $sql = "SELECT imp.*, u.nome as nome_usuario
                    FROM importacoes_csv imp
                    LEFT JOIN usuarios u ON imp.usuario_id = u.id
                    WHERE imp.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar importação por ID {$id}: " . $e->getMessage());
            return false;
        }
    }
}
?>
