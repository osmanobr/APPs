<?php
require_once __DIR__ . '/../core/db.php';
require_once __DIR__ . '/../core/functions.php';

class Pagamento {
    private $pdo;

    public function __construct() {
        $db = DB::getInstance();
        $this->pdo = $db->getConnection();
    }

    /**
     * Cria um novo registro de pagamento.
     *
     * @param array $dados Dados do pagamento:
     *              origem_tipo (ENUM: 'reserva', 'pedido', 'estacionamento'),
     *              origem_id (CHAR(36) - ID da entidade de origem),
     *              inquilino_id (CHAR(36) - opcional),
     *              valor (DECIMAL),
     *              status (ENUM - opcional, default 'pendente'),
     *              forma_pagamento (ENUM - opcional),
     *              data_vencimento (DATE - opcional),
     *              descricao (TEXT - opcional),
     *              comprovante_id_externo (VARCHAR - opcional, para IDs de gateway)
     * @return string|false O UUID do pagamento criado ou false em caso de falha.
     */
    public function create($dados) {
        $uuid = generate_uuid();
        try {
            $sql = "INSERT INTO pagamentos (id, origem_tipo, origem_id, inquilino_id, valor, status, forma_pagamento, data_vencimento, descricao, comprovante_id_externo, criado_em, atualizado_em)
                    VALUES (:id, :origem_tipo, :origem_id, :inquilino_id, :valor, :status, :forma_pagamento, :data_vencimento, :descricao, :comprovante_id_externo, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $stmt = $this->pdo->prepare($sql);

            $status = $dados['status'] ?? 'pendente';
            $inquilino_id = $dados['inquilino_id'] ?? null;
            $forma_pagamento = $dados['forma_pagamento'] ?? null;
            $data_vencimento = $dados['data_vencimento'] ?? null;
            $descricao = $dados['descricao'] ?? null;
            $comprovante_id_externo = $dados['comprovante_id_externo'] ?? null;

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':origem_tipo', $dados['origem_tipo']);
            $stmt->bindParam(':origem_id', $dados['origem_id']);
            $stmt->bindParam(':inquilino_id', $inquilino_id);
            $stmt->bindParam(':valor', $dados['valor']);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':forma_pagamento', $forma_pagamento);
            $stmt->bindParam(':data_vencimento', $data_vencimento);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':comprovante_id_externo', $comprovante_id_externo);

            if ($stmt->execute()) {
                add_log('info', 'pagamento_criado', "Pagamento ID {$uuid} (Valor: {$dados['valor']}, Origem: {$dados['origem_tipo']} ID: {$dados['origem_id']}) criado.", get_logged_in_user_id());
                return $uuid;
            }
            return false;
        } catch (PDOException $e) {
            add_log('erro', 'pagamento_criar_falha_db', "PDOException ao criar pagamento: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao criar pagamento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca um pagamento pelo ID.
     * @param string $id UUID do pagamento.
     * @return array|false
     */
    public function getById($id) {
        try {
            $sql = "SELECT p.*, i.nome as nome_inquilino
                    FROM pagamentos p
                    LEFT JOIN inquilinos i ON p.inquilino_id = i.id
                    WHERE p.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'pagamento_buscar_id_falha_db', "PDOException ao buscar pagamento ID {$id}: " . $e->getMessage());
            error_log("Erro ao buscar pagamento por ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todos os pagamentos de uma origem específica.
     * @param string $origem_tipo Tipo da origem ('reserva', 'pedido', 'estacionamento').
     * @param string $origem_id UUID da origem.
     * @return array Lista de pagamentos.
     */
    public function getByOrigem($origem_tipo, $origem_id) {
        try {
            $sql = "SELECT * FROM pagamentos WHERE origem_tipo = :origem_tipo AND origem_id = :origem_id ORDER BY criado_em DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':origem_tipo', $origem_tipo);
            $stmt->bindParam(':origem_id', $origem_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'pagamento_buscar_origem_falha_db', "PDOException ao buscar pagamentos para {$origem_tipo} ID {$origem_id}: " . $e->getMessage());
            error_log("Erro ao buscar pagamentos por origem: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Atualiza o status e outros dados de um pagamento.
     * Principalmente para registrar um pagamento como 'pago'.
     *
     * @param string $id UUID do pagamento.
     * @param string $status Novo status.
     * @param string|null $forma_pagamento Forma de pagamento utilizada.
     * @param string|null $data_pagamento Data e hora do pagamento.
     * @param string|null $comprovante_id_externo ID externo do comprovante.
     * @param string|null $descricao Adicionar/alterar descrição.
     * @return bool True em sucesso, false em falha.
     */
    public function updatePaymentDetails($id, $status, $forma_pagamento = null, $data_pagamento = null, $comprovante_id_externo = null, $descricao = null) {
        try {
            $pagamento_original = $this->getById($id);
            if(!$pagamento_original) return false;

            $sql = "UPDATE pagamentos SET
                    status = :status,
                    forma_pagamento = :forma_pagamento,
                    data_pagamento = :data_pagamento,
                    comprovante_id_externo = :comprovante_id_externo,
                    descricao = COALESCE(:descricao, descricao), -- Mantém a descrição original se a nova for NULL
                    atualizado_em = CURRENT_TIMESTAMP
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $current_forma_pagamento = $forma_pagamento ?? $pagamento_original['forma_pagamento'];
            $current_data_pagamento = $data_pagamento ?? $pagamento_original['data_pagamento'];
            // Se o status está mudando para 'pago' e não há data de pagamento, define como agora.
            if ($status === 'pago' && $current_data_pagamento === null) {
                $current_data_pagamento = date('Y-m-d H:i:s');
            }
            $current_comprovante = $comprovante_id_externo ?? $pagamento_original['comprovante_id_externo'];
            $current_descricao = $descricao ?? $pagamento_original['descricao'];


            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':forma_pagamento', $current_forma_pagamento);
            $stmt->bindParam(':data_pagamento', $current_data_pagamento);
            $stmt->bindParam(':comprovante_id_externo', $current_comprovante);
            $stmt->bindParam(':descricao', $current_descricao);

            $success = $stmt->execute();
            if ($success) {
                add_log('info', 'pagamento_atualizado', "Pagamento ID {$id} atualizado para status '{$status}'.", get_logged_in_user_id());

                // Aqui podemos adicionar lógica para atualizar o status da entidade de origem (reserva, pedido, etc.)
                // Ex: if ($status === 'pago') { $this->updateOrigemStatus($pagamento_original['origem_tipo'], $pagamento_original['origem_id'], 'pago'); }
            }
            return $success;
        } catch (PDOException $e) {
            add_log('erro', 'pagamento_atualizar_falha_db', "PDOException ao atualizar pagamento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao atualizar pagamento: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Lista todos os pagamentos com detalhes do inquilino e uma breve descrição da origem.
     * @param int $limit Limite de resultados
     * @param int $offset Offset para paginação
     * @return array
     */
    public function getAllWithDetails($limit = 50, $offset = 0) {
        try {
            $sql = "SELECT
                        p.*,
                        i.nome as nome_inquilino,
                        i.email as email_inquilino,
                        CASE p.origem_tipo
                            WHEN 'reserva' THEN (SELECT CONCAT('Reserva Apt: ', apt.numero_apartamento, ' Hotel: ', h.nome) FROM reservas r JOIN apartamentos apt ON r.apartamento_id = apt.id JOIN hoteis h ON apt.hotel_id = h.id WHERE r.id = p.origem_id LIMIT 1)
                            WHEN 'pedido' THEN (SELECT CONCAT('Pedido ID: ', ped.id) FROM pedidos ped WHERE ped.id = p.origem_id LIMIT 1) -- Melhorar para mostrar itens ou total
                            WHEN 'estacionamento' THEN (SELECT CONCAT('Estacionamento Placa: ', est.placa) FROM estacionamentos est WHERE est.id = p.origem_id LIMIT 1)
                            ELSE 'N/A'
                        END as descricao_origem
                    FROM pagamentos p
                    LEFT JOIN inquilinos i ON p.inquilino_id = i.id
                    ORDER BY p.criado_em DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            add_log('erro', 'pagamento_listar_detalhes_falha_db', "PDOException ao listar pagamentos com detalhes: " . $e->getMessage());
            error_log("Erro ao listar pagamentos com detalhes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Conta o total de pagamentos para paginação.
     * @return int
     */
    public function countAll() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) FROM pagamentos");
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erro ao contar pagamentos: " . $e->getMessage());
            return 0;
        }
    }


    // Método auxiliar para atualizar status da entidade de origem (exemplo)
    // Esta lógica pode ser mais complexa e pertencer aos models das entidades de origem.
    /*
    private function updateOrigemStatus($origem_tipo, $origem_id, $novo_status_pagamento_origem) {
        // ... (código existente do método updateOrigemStatus) ...
    }
    */

    /**
     * Permite o acesso à instância PDO para uso em controllers se necessário para transações complexas ou queries específicas.
     * Use com cautela para manter o encapsulamento do model.
     * @return PDO
     */
    public function getPDO() {
        return $this->pdo;
    }

    /**
     * Atualiza campos genéricos de um pagamento.
     * Usado para atualizações que não se encaixam perfeitamente no updatePaymentDetails (ex: vincular origem).
     *
     * @param string $id UUID do pagamento.
     * @param array $campos_valores Array associativo de campos a serem atualizados e seus novos valores.
     * @return bool True em sucesso, false em falha.
     */
    public function updateGeneric($id, $campos_valores) {
        if (empty($campos_valores)) {
            return true; // Nada a atualizar
        }

        $sql_parts = [];
        $params = [':id' => $id]; // Adiciona o ID aos parâmetros desde o início

        foreach ($campos_valores as $campo => $valor) {
            // Validar se o campo é permitido para atualização genérica (whitelist)
            $allowed_fields = ['inquilino_id', 'origem_id', 'origem_tipo', 'status', 'data_pagamento', 'forma_pagamento', 'descricao', 'comprovante_id_externo', 'data_vencimento'];
            if (!in_array($campo, $allowed_fields)) {
                add_log('aviso', 'pagamento_update_generic_campo_invalido', "Tentativa de atualizar campo não permitido '{$campo}' no Pagamento ID {$id}.");
                continue; // Pula campo não permitido
            }
            $sql_parts[] = "`{$campo}` = :{$campo}"; // Usar crase para nomes de campo
            $params[":{$campo}"] = $valor;
        }

        if (empty($sql_parts)) {
            // Isso pode acontecer se todos os campos em $campos_valores forem inválidos
            $_SESSION['error_message'] = "Nenhum campo válido fornecido para atualização genérica do pagamento."; // Informa ao usuário
            return false;
        }

        try {
            $sql = "UPDATE pagamentos SET " . implode(', ', $sql_parts) . ", atualizado_em = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);

            $success = $stmt->execute($params);
            if ($success) {
                add_log('info', 'pagamento_atualizado_generico', "Pagamento ID {$id} atualizado genericamente. Campos: " . implode(', ', array_keys($campos_valores)), get_logged_in_user_id());
            } else {
                $errorInfo = $stmt->errorInfo();
                $_SESSION['error_message'] = "Falha ao executar atualização genérica do pagamento. Detalhe: " . ($errorInfo[2] ?? 'N/A');
                add_log('erro', 'pagamento_update_generic_falha_exec', "Falha ao executar atualização genérica para Pagamento ID {$id}. Erro: " . ($errorInfo[2] ?? 'N/A'), get_logged_in_user_id());
                error_log("Falha ao executar atualização genérica para Pagamento ID {$id}. Erro: " . ($errorInfo[2] ?? 'N/A'));
            }
            return $success;
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "Erro de banco de dados ao tentar atualizar o pagamento genericamente.";
            add_log('erro', 'pagamento_update_generic_falha_db', "PDOException ao atualizar genericamente Pagamento ID {$id}: " . $e->getMessage(), get_logged_in_user_id());
            error_log("Erro ao atualizar pagamento genericamente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calcula o total arrecadado (pagamentos 'pago') em um período.
     * @param string $data_inicio Formato YYYY-MM-DD HH:MM:SS
     * @param string $data_fim Formato YYYY-MM-DD HH:MM:SS
     * @return float
     */
    public function getTotalArrecadadoNoPeriodo($data_inicio, $data_fim) {
        try {
            $sql = "SELECT SUM(valor) FROM pagamentos WHERE status = 'pago' AND data_pagamento BETWEEN :data_inicio AND :data_fim";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':data_inicio', $data_inicio);
            $stmt->bindParam(':data_fim', $data_fim);
            $stmt->execute();
            return (float) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'pagamento_get_total_arrecadado_falha_db', "PDOException: " . $e->getMessage());
            error_log("Erro ao calcular total arrecadado: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Calcula o valor total de pagamentos com status 'pendente'.
     * @return float
     */
    public function getTotalPendente() {
        try {
            $sql = "SELECT SUM(valor) FROM pagamentos WHERE status = 'pendente'";
            $stmt = $this->pdo->query($sql);
            return (float) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'pagamento_get_total_pendente_falha_db', "PDOException: " . $e->getMessage());
            error_log("Erro ao calcular total pendente: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Conta o número de pagamentos "órfãos".
     * Critério: status 'pago' E (inquilino_id IS NULL OU origem_id IS NULL)
     * Este critério pode ser ajustado conforme a necessidade de negócio.
     * @return int
     */
    public function countPagamentosOrfaos() {
        try {
            // Um pagamento é órfão se está PAGO mas não tem inquilino OU não tem origem claramente definida.
            // Ou se tem origem_id mas essa origem não existe mais ou não é válida.
            // Simplificação inicial: PAGO e (inquilino_id IS NULL OU origem_id IS NULL)
            // Uma verificação mais complexa envolveria LEFT JOINs para checar a existência da origem.
            $sql = "SELECT COUNT(id) FROM pagamentos
                    WHERE status = 'pago'
                    AND (inquilino_id IS NULL OR origem_id IS NULL OR origem_tipo IS NULL)";
            // Adicionar verificação se origem_id existe na respectiva tabela seria mais robusto, mas mais complexo para uma contagem simples.
            // Ex: LEFT JOIN reservas ON (pagamentos.origem_tipo = 'reserva' AND pagamentos.origem_id = reservas.id)
            //     WHERE ... AND reservas.id IS NULL (se origem_tipo = reserva)

            $stmt = $this->pdo->query($sql);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            add_log('erro', 'pagamento_count_orfaos_falha_db', "PDOException: " . $e->getMessage());
            error_log("Erro ao contar pagamentos órfãos: " . $e->getMessage());
            return 0;
        }
    }
}
?>
